<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\FormControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;


class ApplicantController extends Controller
{
    public function generateApplicantProfile($id)
    {
        try {
            $applicant = Applicant::findOrFail($id);
            $this->processApplicantImage($applicant);
            $html = view('public.downloadableProfile.DownloadPDFApplicantProfile', compact('applicant'))->render();
            $pdf = $this->createPDF();
            $filename = $this->generateFilename($applicant);
            $tempPdfPath = $this->generateAndSavePDF($pdf, $html, $id);

            return $this->returnPDFResponse($tempPdfPath, $filename);
        } catch (\Exception $e) {
            return $this->handleException($e, 'PDF Generation Error');
        }
    }

    private function processApplicantImage(Applicant $applicant)
    {
        if ($applicant->image) {
            $imagePath = storage_path('app/public/' . str_replace('storage/', '', $applicant->image));
            if (file_exists($imagePath) && is_file($imagePath)) {
                try {
                    $this->validateImage($imagePath);
                    $applicant->image = $imagePath;
                } catch (\Exception $e) {
//                    \Log::error('Image processing error: ' . $e->getMessage());
                    $applicant->image = null;
                }
            } else {
                $applicant->image = null;
            }
        }
    }

    private function validateImage($imagePath)
    {
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo === false) {
            throw new \Exception("Invalid image file");
        }

        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if (filesize($imagePath) > $maxFileSize) {
            throw new \Exception("Image file is too large");
        }
    }

    private function createPDF()
    {
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
            ->setOption('disable-smart-shrinking', false)
            ->setOption('dpi', 300)
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('page-size', 'A4');

        return $pdf;
    }

    private function generateFilename(Applicant $applicant)
    {
        $filename = $applicant->contact['fullName'] ?? 'Applicant-' . $applicant->id;
        $filename .= "-Resume.pdf";
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
    }

    private function generateAndSavePDF($pdf, $html, $id)
    {
        $tempPdfPath = storage_path("app/temp_pdf_{$id}.pdf");
        $pdf->generateFromHtml($html, $tempPdfPath);

        if (!file_exists($tempPdfPath) || filesize($tempPdfPath) == 0) {
            throw new \Exception("PDF file is empty or not generated");
        }

        return $tempPdfPath;
    }

    private function returnPDFResponse($tempPdfPath, $filename)
    {
        return response()->file($tempPdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }

    private function handleException(\Exception $e, $errorType)
    {
        \Log::error($errorType . ': ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());

        return response()->json([
            'error' => $errorType,
            'message' => $e->getMessage(),
        ], 500);
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

    private function applySearchQuery(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->whereRaw("LOWER(summary) LIKE ?", ["%{$searchTerm}%"])
                ->orWhereRaw("LOWER(tools::text) LIKE ?", ["%{$searchTerm}%"])
                ->orWhereRaw("LOWER(speciality::text) LIKE ?", ["%{$searchTerm}%"])
                ->orWhereRaw("LOWER(employment::text) LIKE ?", ["%{$searchTerm}%"]);
        });
    }


    public function searchApplicants(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->input('search');
            $perPage = $request->input('per_page', 12);
            $page = $request->input('page', 1);

            $query = Applicant::query()->where('published', true);

            if ($searchTerm) {
                $searchTermLower = strtolower($searchTerm);
                $isPostgres = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql';

                $query->where(function ($q) use ($searchTermLower, $isPostgres) {
                    $likeOperator = $isPostgres ? 'ILIKE' : 'LIKE';
                    $textCast = $isPostgres ? '::text' : '';

                    $q->whereRaw("LOWER(summary) {$likeOperator} ?", ['%' . $searchTermLower . '%'])
                        ->orWhereRaw("LOWER(tools{$textCast}) {$likeOperator} ?", ['%' . $searchTermLower . '%'])
                        ->orWhereRaw("LOWER(employment{$textCast}) {$likeOperator} ?", ['%' . $searchTermLower . '%'])
                        ->orWhereRaw("LOWER(speciality{$textCast}) {$likeOperator} ?", ['%' . $searchTermLower . '%']);
                });
            }

            $totalResults = $query->count();
            $applicants = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

            $results = [
                'data' => $applicants,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalResults,
                'last_page' => ceil($totalResults / $perPage)
            ];

            return response()->json($results);
        } catch (\Exception $e) {
//            Log::error('Error in searchApplicants: ' . $e->getMessage());
//            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while searching applicants: ' . $e->getMessage()], 500);
        }
    }

    public function getFilteredApplicants(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 12);
            $query = Applicant::query()->where('published', true);

            $this->applyFilters($query, $request);

            $results = $query->paginate($perPage);
            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error in getFilteredApplicants: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            if (config('app.debug')) {
                return response()->json([
                    'error' => 'An error occurred while filtering applicants',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            } else {
                return response()->json(['error' => 'An error occurred while filtering applicants'], 500);
            }
        }
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $isPostgres = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql';

        // Gender
        if ($request->filled('gender')) {
            $genderValue = strtolower($request->input('gender'));
            if ($isPostgres) {
                $query->whereRaw("LOWER(contact->>'gender') ILIKE ?", [$genderValue]);
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.gender'))) LIKE ?", [$genderValue]);
            }
        }

        // City
        if ($request->filled('city')) {
            $cityValue = strtolower($request->input('city'));
            if ($isPostgres) {
                $query->whereRaw("LOWER(contact->>'city') ILIKE ?", [$cityValue]);
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.city'))) LIKE ?", [$cityValue]);
            }

            // Zone (only if city is Baghdad)
            if (strtolower($request->input('city')) === 'baghdad' && $request->filled('zone')) {
                $zoneValue = strtolower($request->input('zone'));
                if ($isPostgres) {
                    $query->whereRaw("LOWER(contact->>'zone') ILIKE ?", [$zoneValue]);
                } else {
                    $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.zone'))) LIKE ?", [$zoneValue]);
                }
            }
        }

        // Age Range
        if ($request->filled('age') && is_array($request->input('age')) && count($request->input('age')) == 2) {
            $ageRange = $request->input('age');
            if ($isPostgres) {
                $query->whereRaw(
                    "CASE WHEN contact->>'birthdate' ~ '^\d{4}-\d{2}-\d{2}$' THEN DATE_PART('year', AGE(CURRENT_DATE, (contact->>'birthdate')::DATE)) ELSE NULL END BETWEEN ? AND ?",
                    [min($ageRange), max($ageRange)]
                );
            } else {
                $query->whereRaw(
                    "CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(contact, '$.birthdate')) REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN YEAR(CURDATE()) - YEAR(STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.birthdate')), '%Y-%m-%d')) ELSE NULL END BETWEEN ? AND ?",
                    [min($ageRange), max($ageRange)]
                );
            }
        }

        // Degree
        if ($request->filled('degree')) {
            $degreeValue = strtolower($request->input('degree'));
            if ($isPostgres) {
                $query->whereRaw("education::jsonb @> ?::jsonb", [json_encode([['degree' => $degreeValue]])]);
            } else {
                $query->whereRaw("JSON_CONTAINS(LOWER(education), JSON_ARRAY(?))", [$degreeValue]);
            }
        }

        // Fresh Graduate
        if ($request->filled('freshGraduate')) {
            $isFreshGraduate = filter_var($request->input('freshGraduate'), FILTER_VALIDATE_BOOLEAN);
            if ($isFreshGraduate) {
                $twoYearsAgo = now()->subYears(2)->startOfYear()->year;
                $currentYear = now()->year;
                if ($isPostgres) {
                    $query->whereRaw(
                        "education::jsonb @> ?::jsonb AND (education->0->>'degree')::text ILIKE ? AND (education->0->'duration'->1)::int BETWEEN ? AND ?",
                        [json_encode([['degree' => "Bachelor's Degree"]]), strtolower("Bachelor's Degree"), $twoYearsAgo, $currentYear]
                    );
                } else {
                    $query->whereRaw(
                        "JSON_CONTAINS(education, ?, '$') AND LOWER(JSON_UNQUOTE(JSON_EXTRACT(education, '$[0].degree'))) = ? AND CAST(JSON_UNQUOTE(JSON_EXTRACT(education, '$[0].duration[1]')) AS UNSIGNED) BETWEEN ? AND ?",
                        [json_encode(['degree' => "Bachelor's Degree"]), strtolower("Bachelor's Degree"), $twoYearsAgo, $currentYear]
                    );
                }
            }
        }

        // Work Availability
        if ($request->filled('workAvailability')) {
            $isAvailable = filter_var($request->input('workAvailability'), FILTER_VALIDATE_BOOLEAN);
            if ($isPostgres) {
                $query->whereRaw("(contact->>'workAvailability')::boolean = ?", [$isAvailable]);
            } else {
                $query->whereRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.workAvailability')) AS UNSIGNED) = ?", [$isAvailable ? 1 : 0]);
            }
        }

        // Experience
        if ($request->filled('experience') && is_array($request->input('experience')) && count($request->input('experience')) == 2) {
            $experienceRange = $request->input('experience');
            if ($isPostgres) {
                $query->whereRaw(
                    "COALESCE((SELECT SUM(CASE WHEN (job->>'duration')::json->>1 = 'present' THEN EXTRACT(YEAR FROM CURRENT_DATE) - ((job->>'duration')::json->>0)::int ELSE ((job->>'duration')::json->>1)::int - ((job->>'duration')::json->>0)::int END) FROM jsonb_array_elements(employment::jsonb) AS job), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            } else {
                $query->whereRaw(
                    "COALESCE((SELECT SUM(CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[1]')) = 'present' THEN YEAR(CURDATE()) - CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[0]')) AS UNSIGNED) ELSE CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[1]')) AS UNSIGNED) - CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[0]')) AS UNSIGNED) END) FROM JSON_TABLE(employment, '$[*]' COLUMNS (job JSON PATH '$')) AS jobs), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            }
        }

        // Main Specializations
        if ($request->filled('mainSpecializations') && is_array($request->input('mainSpecializations'))) {
            $mainSpecializations = $request->input('mainSpecializations');
            if ($isPostgres) {
                $query->whereIn(DB::raw("speciality->>'parent'"), $mainSpecializations);
            } else {
                $query->whereIn(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(speciality, '$.parent'))"), $mainSpecializations);
            }
        }

        // Sub Specialities
        if ($request->filled('subSpecialities') && is_array($request->input('subSpecialities'))) {
            $subSpecialities = $request->input('subSpecialities');
            if ($isPostgres) {
                $query->whereRaw(
                    "speciality->>'children' ?| array[" . implode(',', array_fill(0, count($subSpecialities), '?')) . "]",
                    $subSpecialities
                );
            } else {
                $query->whereRaw(
                    "JSON_OVERLAPS(JSON_EXTRACT(speciality, '$.children'), JSON_ARRAY(" . implode(',', array_fill(0, count($subSpecialities), '?')) . "))",
                    $subSpecialities
                );
            }
        }
    }
    public function store(Request $request)
    {
        try {
            $requestData = json_decode($request->input('data'), true);
            $user = Auth::user();
            $applicant = Applicant::firstOrNew(['user_id' => $user->id]);

            if ($request->hasFile('image')) {
                $this->handleImageUpload($request, $applicant);
            }

            $this->updateApplicantFields($applicant, $requestData);

            $applicant->save();

            return response()->json([
                'message' => $applicant->wasRecentlyCreated ? 'Applicant data saved successfully' : 'Applicant data updated successfully',
                'data' => $applicant
            ], $applicant->wasRecentlyCreated ? 201 : 200);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error in store method');
        }
    }

    private function handleImageUpload(Request $request, Applicant $applicant)
    {
        $file = $request->file('image');
        $request->validate(['image' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('applicant_images', $filename, 'public');

        if (!$path) {
            throw new \Exception('Failed to save the image.');
        }

        if ($applicant->image) {
            Storage::disk('public')->delete($applicant->image);
        }

        $applicant->image = $path;
    }

    private function updateApplicantFields(Applicant $applicant, array $requestData)
    {
        $fields = ['speciality', 'education', 'languages', 'skills', 'tools', 'summary', 'courses', 'contact', 'employment', 'activities', 'published'];
        foreach ($fields as $field) {
            $applicant->$field = $requestData[$field] ?? $applicant->$field;
        }
    }

    public function showPersonalProfile()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $userId = Auth::id();
        $applicant = Applicant::firstOrCreate(['user_id' => $userId]);

        return response()->json($applicant, 200);
    }

    public function showResume($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicantData = $applicant->toArray();

        if (isset($applicantData['contact']['showPhone'])) {
            if (!$applicantData['contact']['showPhone']) {
                unset($applicantData['contact']['phone']);
            }
            unset($applicantData['contact']['showPhone']);
        }

        return response()->json($applicantData, 200);
    }

    public function getAuthUser()
    {
        $user = Auth::user()->load("applicant");
        return response()->json($user);
    }

    // Placeholder methods
    public function create() {}
    public function edit(Applicant $applicant) {}
    public function update(Request $request, Applicant $applicant) {}
    public function destroy(Applicant $applicant) {}
}
