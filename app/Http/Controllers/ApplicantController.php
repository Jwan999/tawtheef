<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use App\Models\FormControl;

class ApplicantController extends Controller
{
    public function generateApplicantProfile($id)
    {
        try {
            $applicant = Applicant::findOrFail($id);

            if ($applicant->image) {
                $applicant->image = str_replace('/storage//', '/storage/', Storage::url($applicant->image));
            }

            $filename = "applicant_profile_{$id}.pdf";
            $html = view('public.downloadableProfile.DownloadPDFApplicantProfile', compact('applicant'))->render();
            $baseUrl = rtrim(config('app.url'), '/');

            // Use system-wide Chromium installation
            $chromiumPath = '/usr/bin/chromium-browser';

            // If system-wide Chromium is not available, fall back to Puppeteer's Chromium
            if (!file_exists($chromiumPath)) {
                $chromiumPath = trim(shell_exec('node -e "console.log(require(\'puppeteer\').executablePath())"'));
            }

            if (!$chromiumPath) {
                throw new \Exception('Chromium executable not found');
            }

            // Create a temporary directory that PHP can write to
            $tempDir = sys_get_temp_dir() . '/browsershot_' . uniqid();
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $pdf = Browsershot::html($html)
                ->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->setChromePath($chromiumPath)
                ->noSandbox()
                ->ignoreHttpsErrors()
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-gpu',
                    '--disable-web-security',
                    '--user-data-dir=' . $tempDir,
                ])
                ->setOption('env', [
                    'PUPPETEER_CACHE_DIR' => $tempDir,
                ])
                ->format('A4')
                ->waitUntilNetworkIdle()
                ->showBackground()
                ->timeout(120000)
                ->setBaseUrl($baseUrl)
                ->setOption('addStyleTag', json_encode(['content' => '
                img { visibility: hidden; }
                img[src^="data:"], img[src^="http"] { visibility: visible; }
            ']))
                ->setOption('evaluateScript', '
                setTimeout(() => {
                    const images = document.querySelectorAll("img");
                    images.forEach(img => {
                        if (img.complete) img.style.visibility = "visible";
                        img.onload = () => img.style.visibility = "visible";
                        img.onerror = () => console.error("Failed to load image:", img.src);
                    });
                }, 1000);
            ')
                ->pdf();

            // Clean up the temporary directory
            $this->recursiveRemoveDirectory($tempDir);

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Chromium path: ' . ($chromiumPath ?? 'Not found'));

            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

// Helper function to recursively remove a directory
    private function recursiveRemoveDirectory($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        $this->recursiveRemoveDirectory($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
            rmdir($dir);
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

        return response()->json($query->get());
    }

    public function getFilteredApplicants(Request $request)
    {

        $query = Applicant::query()->where('published', true);

        if ($request->filled('experience')) {
            $experience_range = $request->input('experience');

            if (is_array($experience_range) && count($experience_range) == 2
                && ($experience_range !== [2, 6])) { // Check if it's not the default value
                $experience_min = min($experience_range);
                $experience_max = max($experience_range);

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
            ", [$experience_min, $experience_max]);
            }
        }

        if ($request->filled('age')) {
            $age_range = $request->input('age');

            if (is_array($age_range) && count($age_range) == 2
                && ($age_range !== [19, 26])) { // Check if it's not the default value
                $age_min = min($age_range);
                $age_max = max($age_range);

                $query->whereRaw("
                CASE
                    WHEN contact->>'birthdate' ~ '^\d{4}-\d{2}-\d{2}$' THEN
                        DATE_PART('year', AGE(CURRENT_DATE, (contact->>'birthdate')::DATE))
                    ELSE
                        NULL
                END BETWEEN ? AND ?
            ", [$age_min, $age_max]);
            }
        }


        if ($request->filled('gender')) {
            $gender = $request->input('gender');
            $query->whereRaw("contact->>'gender' = ?", [$gender]);
        }

        if ($request->filled('workAvailability')) {
            $workAvailability = $request->input('workAvailability');
            $query->whereRaw("details->>'workAvailability' = ?", [$workAvailability]);
        }

        // Fresh Graduate filter
        if ($request->filled('freshGraduate') && $request->input('freshGraduate') == true) {
            $currentYear = date('Y');
            $twoYearsAgo = $currentYear - 2;

            $query->whereRaw("
            EXISTS (
            SELECT 1 FROM jsonb_array_elements(education::jsonb) AS edu
            WHERE (edu->'duration'->>1) ~ '^[0-9]+$' AND (edu->'duration'->>1)::int BETWEEN ? AND ?
            )
            ", [$twoYearsAgo, $currentYear]);
        }

//        if ($request->filled('degree')) {
//            $degree = $request->input('degree');
//            $query->whereRaw("EXISTS (
//            SELECT 1 FROM jsonb_array_elements(education::jsonb) AS edu
//            WHERE edu->>'degree' = ?)", [$degree]);
//        }

        if ($request->filled('city')) {
            $city = $request->input('city');
            $query->whereRaw("contact->>'city' = ?", [$city]);
        }


        if ($request->filled('zone') && $request->input('city') === 'Baghdad') {
            $zone = $request->input('zone');
            $query->whereRaw("contact->>'zone' = ?", [$zone]);
        }

        $totalCount = $query->count();
        \Log::info("Total applicants before filtering: " . $totalCount);

        if ($request->filled('mainSpecializations')) {
            $mainSpecializations = $request->input('mainSpecializations');
            \Log::info("Filtering by main specialization: " . json_encode($mainSpecializations));
            $query->whereJsonContains('speciality->title', $mainSpecializations[0]);

            // Debugging: Get the count after applying main specialization filter
            $countAfterMain = $query->count();
            \Log::info("Applicants after main specialization filter: " . $countAfterMain);
        }

        if ($request->filled('subSpecialities')) {
            $subSpecialities = $request->input('subSpecialities');
            \Log::info("Filtering by sub specialities: " . json_encode($subSpecialities));
            $query->where(function ($q) use ($subSpecialities) {
                foreach ($subSpecialities as $subSpeciality) {
                    $q->orWhereJsonContains('speciality->children', $subSpeciality);
                }
            });

            // Debugging: Get the count after applying sub specialities filter
            $countAfterSub = $query->count();
            \Log::info("Applicants after sub specialities filter: " . $countAfterSub);
        }

        $applicants = $query->get();
//        dd($applicants);
        return response()->json($applicants, 200);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

        $requestData = json_decode($request->input('data'), true);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('applicant_images', 'public');
        }


        $user = Auth::user();
        $applicant = Applicant::where('user_id', $user->id)->first();

        if ($applicant) {

            $applicant->image = $imagePath ?: $applicant->image;
            $applicant->speciality = $requestData['speciality'];
            $applicant->education = $requestData['education'];
            $applicant->languages = $requestData['languages'];
            $applicant->skills = $requestData['skills'];
            $applicant->tools = $requestData['tools'];
            $applicant->summary = $requestData['summary'];
            $applicant->courses = $requestData['courses'];
            $applicant->contact = $requestData['contact'];
            $applicant->employment = $requestData['employment'];
            $applicant->activities = $requestData['activities'];
            $applicant->published = $requestData['published'];

            $applicant->save();

            return response()->json(['message' => 'Applicant data updated successfully', 'data' => $applicant], 200);
        } else {
            $applicant = new Applicant();
            $applicant->image = $imagePath;
            $applicant->speciality = $requestData['speciality'];
            $applicant->education = $requestData['education'];
            $applicant->languages = $requestData['languages'];
            $applicant->skills = $requestData['skills'];
            $applicant->tools = $requestData['tools'];
            $applicant->summary = $requestData['summary'];
            $applicant->courses = $requestData['courses'];
            $applicant->contact = $requestData['contact'];
            $applicant->employment = $requestData['employment'];
            $applicant->activities = $requestData['activities'];
            $applicant->published = $requestData['published'];
            $applicant->user_id = $user->id;

            $applicant->save();

            return response()->json(['message' => 'Applicant data saved successfully', 'data' => $applicant], 201);
        }
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
        return response()->json($applicant, 200);
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
