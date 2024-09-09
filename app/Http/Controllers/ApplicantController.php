<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FormControl;
use Knp\Snappy\Pdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ApplicantController extends Controller
{

    public function generateApplicantProfile($id)
    {
        try {
            $applicant = Applicant::findOrFail($id);

            // Process the image
            if ($applicant->image) {
                $imagePath = storage_path('app/public/' . str_replace('storage/', '', $applicant->image));
                if (file_exists($imagePath) && is_file($imagePath)) {
                    try {
                        // Get image info
                        $imageInfo = getimagesize($imagePath);
                        if ($imageInfo === false) {
                            throw new \Exception("Invalid image file");
                        }

                        // Check file size
                        $maxFileSize = 5 * 1024 * 1024; // 5 MB
                        if (filesize($imagePath) > $maxFileSize) {
                            throw new \Exception("Image file is too large");
                        }

                        // Use the file path directly
                        $applicant->image = $imagePath;
                    } catch (\Exception $e) {
                        \Log::error('Image processing error: ' . $e->getMessage());
                        $applicant->image = null;
                    }
                } else {
                    $applicant->image = null;
                }
            }

            // Render the view
            $html = view('public.downloadableProfile.DownloadPDFApplicantProfile', compact('applicant'))->render();

            // Create PDF
            $pdf = app('snappy.pdf.wrapper');
            $pdf->setOption('enable-local-file-access', true)
                ->setOption('javascript-delay', 5000)
                ->setOption('no-stop-slow-scripts', true)
                ->setOption('enable-external-links', true)
                ->setOption('enable-internal-links', true)
                ->setOption('print-media-type', true)
                ->setOption('no-background', false)
                ->setOption('lowquality', false)
                ->setOption('encoding', 'UTF-8')
                ->setOption('zoom', 1)
                ->setOption('disable-smart-shrinking', false)  // Changed to false
                ->setOption('dpi', 300)
                ->setOption('margin-top', 0)  // Added small margins
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('page-size', 'A4');

            // Filename generation
            $filename = '';
            if (isset($applicant->contact['fullName']) && !empty($applicant->contact['fullName'])) {
                $filename = $applicant->contact['fullName'];
            } elseif (isset($applicant->contact['fullName']) && !empty($applicant->contact['fullName'])) {
                $filename = $applicant->contact['fullName'];
            } else {
                $filename = 'Applicant-' . $applicant->id;
            }

            $filename .= "-Resume.pdf";
            $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename); // Sanitize filename

            // Generate and save the PDF
            $tempPdfPath = storage_path("app/temp_pdf_{$id}.pdf");
            $pdf->generateFromHtml($html, $tempPdfPath);

            if (!file_exists($tempPdfPath) || filesize($tempPdfPath) == 0) {
                throw new \Exception("PDF file is empty or not generated");
            }

            return response()->file($tempPdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewApplicantProfile($id)
    {
        $applicant = Applicant::findOrFail($id);
        return view('public.downloadableProfile.ViewPDFApplicantProfile', compact('applicant'));
    }

    public function getSelectables(Request $request)
    {
        $key = $request->key;

        if (!$key) {
            return response()->json(['error' => 'Key is required'], 400);
        }

        $formControl = FormControl::where('key', $key)->first();

        if (!$formControl) {
            return response()->json(['error' => 'No data found for the given key'], 404);
        }

        return response()->json(json_decode($formControl->value));
    }

    public function index()
    {
        $applicants = Applicant::where('published', true)->get();
        return response()->json($applicants);
    }

    public function searchApplicants(Request $request)
    {
        $searchTerm = $request->input('search');
        $perPage = $request->input('per_page', 12); // Default to 12 items per page

        $query = Applicant::query()->where('published', true);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where(Applicant::COLUMN_SUMMARY, 'ILIKE', "%{$searchTerm}%")
                    ->orWhereRaw(Applicant::COLUMN_TOOLS . "::text ILIKE ?", ["%{$searchTerm}%"])
                    ->orWhereRaw("EXISTS (
                      SELECT 1 FROM json_array_elements(" . Applicant::COLUMN_EMPLOYMENT . "::json) AS job,
                      json_array_elements_text(job->'responsibilities') AS responsibility
                      WHERE responsibility ILIKE ?
                  )", ["%{$searchTerm}%"])
                    ->orWhereRaw(Applicant::COLUMN_SPECIALITY . "->>'children' ILIKE ?", ["%{$searchTerm}%"]);
            });
        }

        $applicants = $query->paginate($perPage);

        return response()->json($applicants);
    }

    public function getFilteredApplicants(Request $request)
    {
        try {
//            dump('Starting getFilteredApplicants with parameters: ' . json_encode($request->all()));

            $perPage = $request->input('per_page', 12);
            $query = Applicant::query()->where('published', true);

            $filterMethods = [
                'gender' => $this->filterGender($query, $request),
                'city' => $this->filterCity($query, $request),
                'age' => $this->filterAge($query, $request),
                'degree' => $this->filterDegree($query, $request),
                'freshGraduate' => $this->filterFreshGraduate($query, $request),
                'workAvailability' => $this->filterWorkAvailability($query, $request),
                'experience' => $this->filterExperience($query, $request),
                'mainSpecializations' => $this->filterMainSpecializations($query, $request),
                'subSpecialities' => $this->filterSubSpecialities($query, $request),
            ];

            foreach ($filterMethods as $method) {
                if (is_callable($method)) {
                    $method();
                }
            }

            $results = $query->paginate($perPage);

            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error in getFilteredApplicants: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    private function filterGender($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('gender')) {
                $query->whereRaw("LOWER(contact->>'gender') = ?", [strtolower($request->input('gender'))]);
            }
        };
    }

    private function filterCity($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('city')) {
                $query->whereRaw("LOWER(contact->>'city') = ?", [strtolower($request->input('city'))]);

                if ($request->input('city') === 'Baghdad' && $request->filled('zone')) {
                    $query->whereRaw("LOWER(contact->>'zone') = ?", [strtolower($request->input('zone'))]);
                }
            }
        };
    }

    private function filterAge($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('age')) {
                $ageRange = $request->input('age');
                if (is_array($ageRange) && count($ageRange) == 2) {
                    $query->whereRaw("
                    CASE
                        WHEN contact->>'birthdate' ~ '^\d{4}-\d{2}-\d{2}$' THEN
                            DATE_PART('year', AGE(CURRENT_DATE, (contact->>'birthdate')::DATE))
                        ELSE
                            NULL
                    END BETWEEN ? AND ?
                ", [min($ageRange), max($ageRange)]);
                }
            }
        };
    }

    private function filterDegree($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('degree')) {
                $query->whereRaw("EXISTS (
                SELECT 1 FROM jsonb_array_elements(education::jsonb) AS edu
                WHERE LOWER(edu->>'degree') = ?)", [strtolower($request->input('degree'))]);
            }
        };
    }

    private function filterFreshGraduate($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('freshGraduate')) {
                $isFreshGraduate = filter_var($request->input('freshGraduate'), FILTER_VALIDATE_BOOLEAN);

                if ($isFreshGraduate) {
                    $twoYearsAgo = now()->subYears(2)->startOfYear();
                    $query->whereRaw("
                    EXISTS (
                        SELECT 1
                        FROM jsonb_array_elements(education::jsonb) AS edu
                        WHERE
                            LOWER(edu->>'degree') = LOWER('Bachelor''s Degree')
                            AND (
                                CASE
                                    WHEN jsonb_typeof(edu->'duration') = 'array'
                                        AND jsonb_array_length(edu->'duration') = 2
                                    THEN
                                        (edu->'duration'->1)::text::int
                                    ELSE
                                        NULL
                                END
                            ) BETWEEN ? AND ?
                    )
                ", [$twoYearsAgo->year, now()->year]);
                }
            }
        };
    }

    private function filterWorkAvailability($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('workAvailability')) {
                $isAvailable = filter_var($request->input('workAvailability'), FILTER_VALIDATE_BOOLEAN);
                $query->whereRaw("(details->>'workAvailability')::boolean = ?", [$isAvailable]);
            }
        };
    }

    private function filterExperience($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('experience')) {
                $experienceRange = $request->input('experience');
                if (is_array($experienceRange) && count($experienceRange) == 2) {
                    $query->whereRaw("
                    COALESCE((
                        SELECT SUM(
                            CASE
                                WHEN (job->>'duration')::json->>1 = 'present' THEN
                                    EXTRACT(YEAR FROM CURRENT_DATE) - ((job->>'duration')::json->>0)::int
                                ELSE
                                    ((job->>'duration')::json->>1)::int - ((job->>'duration')::json->>0)::int
                            END
                        )
                        FROM json_array_elements(employment::json) AS job
                    ), 0) BETWEEN ? AND ?
                ", [min($experienceRange), max($experienceRange)]);
                }
            }
        };
    }

    private function filterMainSpecializations($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('mainSpecializations')) {
                $mainSpecializations = $request->input('mainSpecializations');
                if (is_array($mainSpecializations) && !empty($mainSpecializations)) {
                    $query->whereRaw("speciality->>'parent' IN (?)", [implode(',', $mainSpecializations)]);
                }
            }
        };
    }

    private function filterSubSpecialities($query, $request)
    {
        return function () use ($query, $request) {
            if ($request->filled('subSpecialities')) {
                $subSpecialities = $request->input('subSpecialities');
                if (is_array($subSpecialities) && !empty($subSpecialities)) {
                    $query->whereRaw("speciality->>'children' ?| array[?]", [implode(',', $subSpecialities)]);
                }
            }
        };
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->input('data'), true);

        $user = Auth::user();
        $applicant = Applicant::where('user_id', $user->id)->first();

        if (!$applicant) {
            $applicant = new Applicant();
            $applicant->user_id = $user->id;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $this->handleImageUpload($request, $applicant);
        }

        // Update or set other applicant fields
        $fields = ['speciality', 'education', 'languages', 'skills', 'tools', 'summary', 'courses', 'contact', 'employment', 'activities', 'published'];
        foreach ($fields as $field) {
            $applicant->$field = $requestData[$field] ?? $applicant->$field;
        }

        $applicant->save();

        return response()->json([
            'message' => $applicant->wasRecentlyCreated ? 'Applicant data saved successfully' : 'Applicant data updated successfully',
            'data' => $applicant
        ], $applicant->wasRecentlyCreated ? 201 : 200);
    }

    private function handleImageUpload(Request $request, Applicant $applicant)
    {
        $file = $request->file('image');

        // Validate file
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // max 2MB
        ]);

        // Generate a unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $path = $file->storeAs('applicant_images', $filename, 'public');

        if (!$path) {
            throw new \Exception('Failed to save the image.');
        }

        // Delete old image if exists
        if ($applicant->image) {
            Storage::disk('public')->delete($applicant->image);
        }

        $applicant->image = $path;
    }


    public function showPersonalProfile()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Get the ID of the authenticated user
        $userId = Auth::id();

        // Find the applicant associated with the authenticated user
        $applicant = Applicant::where('user_id', $userId)->first();

        // If the applicant does not exist, create a new one
        if (!$applicant) {
            $applicant = new Applicant();
            $applicant->user_id = $userId;
            $applicant->save();

            return response()->json([
                'message' => 'New applicant created for the authenticated user',
                'applicant_id' => $applicant->id,
            ], 200);
        }

        // Return the existing applicant data as JSON
        return response()->json($applicant, 200);
    }

    public function showResume($id)
    {
        $applicant = Applicant::find($id);

        // If the applicant is not found, return a 404 response
        if (!$applicant) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        // Convert the applicant to an array
        $applicantData = $applicant->toArray();

        // Check if the contact object and showPhone property exist
        if (isset($applicantData['contact']) && isset($applicantData['contact']['showPhone'])) {
            // If showPhone is false, remove the phone number
            if (!$applicantData['contact']['showPhone']) {
                unset($applicantData['contact']['phone']);
            }
            // Remove the showPhone property itself as it's not needed in the response
            unset($applicantData['contact']['showPhone']);
        }

        return response()->json($applicantData, 200);
    }

    public function getAuthUser()
    {

        $user = Auth::user()->load("applicant");

        return response()->json($user);
    }

    public function edit(Applicant $applicant)
    {
        //
    }

    public function update(Request $request, Applicant $applicant)
    {
        //
    }

    public function destroy(Applicant $applicant)
    {

    }
}
