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

        $perPage = $request->input('per_page', 12); // Default to 12 items per page

        $query = Applicant::query()->where('published', true);

        if ($request->filled('gender')) {
            $gender = $request->input('gender');
            $query->whereRaw("contact->>'gender' = ?", [$gender]);
        }

//        if ($request->filled('experience')) {
//            $experience_range = $request->input('experience');
//
//            if (is_array($experience_range) && count($experience_range) == 2
//                && ($experience_range !== [2, 6])) { // Check if it's not the default value
//                $experience_min = min($experience_range);
//                $experience_max = max($experience_range);
//
//                $query->whereRaw("
//                COALESCE((
//                    SELECT SUM(
//                        CASE
//                            WHEN (job->>'duration')::json->>1 = 'present' THEN
//                                EXTRACT(YEAR FROM CURRENT_DATE) - ((job->>'duration')::json->>0)::int
//                            ELSE
//                                ((job->>'duration')::json->>1)::int - ((job->>'duration')::json->>0)::int
//                        END
//                    )
//                    FROM json_array_elements(employment::json) AS job
//                ), 0) BETWEEN ? AND ?
//            ", [$experience_min, $experience_max]);
//            }
//        }

//        if ($request->filled('age')) {
//            $age_range = $request->input('age');
//
//            if (is_array($age_range) && count($age_range) == 2
//                && ($age_range !== [19, 26])) { // Check if it's not the default value
//                $age_min = min($age_range);
//                $age_max = max($age_range);
//
//                $query->whereRaw("
//                CASE
//                    WHEN contact->>'birthdate' ~ '^\d{4}-\d{2}-\d{2}$' THEN
//                        DATE_PART('year', AGE(CURRENT_DATE, (contact->>'birthdate')::DATE))
//                    ELSE
//                        NULL
//                END BETWEEN ? AND ?
//            ", [$age_min, $age_max]);
//            }
//
//        }

//        if ($request->filled('workAvailability')) {
//            $workAvailability = $request->input('workAvailability');
//            $query->whereRaw("details->>'workAvailability' = ?", [$workAvailability]);
//        }

//        if ($request->filled('freshGraduate') && $request->input('freshGraduate') == true) {
//            $currentYear = (int)date('Y');
//            $twoYearsAgo = $currentYear - 2;
//
//            $query->whereRaw("
//    EXISTS (
//        SELECT 1
//        FROM jsonb_array_elements(education::jsonb) AS edu
//        WHERE
//            (edu->>'graduationYear')::int BETWEEN ? AND ?
//            OR
//            (edu->'duration'->1)::text IN (?, ?, ?, 'present')
//    )
//    ", [$twoYearsAgo, $currentYear, (string)$twoYearsAgo, (string)($twoYearsAgo + 1), (string)$currentYear]);
//
//            \Log::info("Fresh Graduate SQL: " . $query->toSql());
//            \Log::info("Fresh Graduate Bindings: " . json_encode($query->getBindings()));
//        }


        if ($request->filled('degree')) {
            $degree = $request->input('degree');
            $query->whereRaw("EXISTS (
            SELECT 1 FROM jsonb_array_elements(education::jsonb) AS edu
            WHERE edu->>'degree' = ?)", [$degree]);
        }



//        if ($request->filled('city')) {
//            $city = $request->input('city');
//            $query->whereRaw("contact->>'city' = ?", [$city]);
//        }
//
//        if ($request->filled('zone') && $request->input('city') === 'Baghdad') {
//            $zone = $request->input('zone');
//            $query->whereRaw("contact->>'zone' = ?", [$zone]);
//        }
//        dd($query->get());

        $totalCount = $query->count();
        \Log::info("Total applicants before filtering: " . $totalCount);

//        if ($request->filled('mainSpecializations')) {
//            $mainSpecializations = $request->input('mainSpecializations');
//            \Log::info("Filtering by main specialization: " . json_encode($mainSpecializations));
//            $query->whereJsonContains('speciality->title', $mainSpecializations[0]);
//
//            // Debugging: Get the count after applying main specialization filter
//            $countAfterMain = $query->count();
//            \Log::info("Applicants after main specialization filter: " . $countAfterMain);
//        }

//        if ($request->filled('subSpecialities')) {
//            $subSpecialities = $request->input('subSpecialities');
//            \Log::info("Filtering by sub specialities: " . json_encode($subSpecialities));
//            $query->where(function ($q) use ($subSpecialities) {
//                foreach ($subSpecialities as $subSpeciality) {
//                    $q->orWhereJsonContains('speciality->children', $subSpeciality);
//                }
//            });
//
//            // Debugging: Get the count after applying sub specialities filter
//            $countAfterSub = $query->count();
//            \Log::info("Applicants after sub specialities filter: " . $countAfterSub);
//        }
        $applicants = $query->paginate($perPage);

        return response()->json($applicants);
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
