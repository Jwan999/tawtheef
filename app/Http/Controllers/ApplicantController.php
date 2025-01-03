<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\FormControl;
use App\Services\ApplicantFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;

class ApplicantController extends Controller
{
    protected $filterService;

    public function __construct(ApplicantFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getStatistics()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $isPostgres = $driver === 'pgsql';

        $totalResumes = Applicant::count();
        $PublishedResumes = Applicant::where('published',true)->count();

        $availableForWork = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("(contact->>'workAvailability')::boolean = true");
            }, function ($query) {
                return $query->whereJsonContains('contact->workAvailability', true);
            })
            ->count();

        $bachelorOrHigher = Applicant::where('published', true)
            ->where(function ($query) use ($isPostgres) {
                if ($isPostgres) {
                    $query->whereRaw("education::jsonb @> '[{\"degree\": \"Bachelor''s Degree\"}]'::jsonb")
                        ->orWhereRaw("education::jsonb @> '[{\"degree\": \"Master''s Degree\"}]'::jsonb")
                        ->orWhereRaw("education::jsonb @> '[{\"degree\": \"Doctorate (Ph.D.)\"}]'::jsonb");
                } else {
                    $query->whereJsonContains('education', ['degree' => "Bachelor's Degree"])
                        ->orWhereJsonContains('education', ['degree' => "Master's Degree"])
                        ->orWhereJsonContains('education', ['degree' => "Doctorate (Ph.D.)"]);
                }
            })
            ->count();

        $undergraduates = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("education::jsonb @> '[{\"degree\": \"Undergraduate\"}]'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('education', ['degree' => "Undergraduate"]);
            })
            ->count();

        $creativeDesign = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("speciality::jsonb @> '{\"specializations\": [\"Creative & Design\"]}'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('speciality->specializations', 'Creative & Design');
            })
            ->count();

        $engineering = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("speciality::jsonb @> '{\"specializations\": [\"Science & Engineering\"]}'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('speciality->specializations', 'Science & Engineering');
            })
            ->count();

        $graphicDesign = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("speciality::jsonb @> '{\"children\": [\"Graphic Design\"]}'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('speciality->children', 'Graphic Design');
            })
            ->count();

        $development = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("speciality::jsonb @> '{\"specializations\": [\"Development\"]}'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('speciality->specializations', 'Development');
            })
            ->count();

        $maleCandidates = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("contact->>'gender' = 'Male'");
            }, function ($query) {
                return $query->whereJsonContains('contact->gender', 'Male');
            })
            ->count();

        $femaleCandidates = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("contact->>'gender' = 'Female'");
            }, function ($query) {
                return $query->whereJsonContains('contact->gender', 'Female');
            })
            ->count();

        $microsoftOffice = Applicant::where('published', true)
            ->where(function ($query) use ($isPostgres) {
                if ($isPostgres) {
                    $query->whereRaw("tools::jsonb @> '[{\"item\": \"Microsoft Office\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"MS Office\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"MS Word\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"MS Excel\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"MS PowerPoint\"}]'::jsonb");
                } else {
                    $query->whereJsonContains('tools', ['item' => 'Microsoft Office'])
                        ->orWhereJsonContains('tools', ['item' => 'MS Office'])
                        ->orWhereJsonContains('tools', ['item' => 'MS Word'])
                        ->orWhereJsonContains('tools', ['item' => 'MS Excel'])
                        ->orWhereJsonContains('tools', ['item' => 'MS PowerPoint']);
                }
            })
            ->count();

        $adobeSuite = Applicant::where('published', true)
            ->where(function ($query) use ($isPostgres) {
                if ($isPostgres) {
                    $query->whereRaw("tools::jsonb @> '[{\"item\": \"Adobe\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"Photoshop\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"Illustrator\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"InDesign\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"Premiere Pro\"}]'::jsonb")
                        ->orWhereRaw("tools::jsonb @> '[{\"item\": \"After Effects\"}]'::jsonb");
                } else {
                    $query->whereJsonContains('tools', ['item' => 'Adobe'])
                        ->orWhereJsonContains('tools', ['item' => 'Photoshop'])
                        ->orWhereJsonContains('tools', ['item' => 'Illustrator'])
                        ->orWhereJsonContains('tools', ['item' => 'InDesign'])
                        ->orWhereJsonContains('tools', ['item' => 'Premiere Pro'])
                        ->orWhereJsonContains('tools', ['item' => 'After Effects']);
                }
            })
            ->count();

        $bilingualCandidates = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("languages::jsonb @> '[{\"item\": \"Arabic\"}]'::jsonb")
                    ->whereRaw("languages::jsonb @> '[{\"item\": \"English\"}]'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('languages', ['item' => 'Arabic'])
                    ->whereJsonContains('languages', ['item' => 'English']);
            })
            ->count();

        $businessManagement = Applicant::where('published', true)
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("speciality::jsonb @> '{\"specializations\": [\"Business & Management\"]}'::jsonb");
            }, function ($query) {
                return $query->whereJsonContains('speciality->specializations', 'Business & Management');
            })
            ->count();

        $eventParticipants = Applicant::where('published', true)
            ->whereNotNull('activities')
            ->when($isPostgres, function ($query) {
                return $query->whereRaw("jsonb_array_length(activities::jsonb) > 0");
            }, function ($query) {
                return $query->whereRaw("JSON_LENGTH(activities) > 0");
            })
            ->count();

        return response()->json([
            'totalResumes' => $totalResumes,
            'PublishedResumes' => $PublishedResumes,
            'availableForWork' => $availableForWork,
            'bachelorOrHigher' => $bachelorOrHigher,
            'undergraduates' => $undergraduates,
            'creativeDesign' => $creativeDesign,
            'engineering' => $engineering,
            'graphicDesign' => $graphicDesign,
            'development' => $development,
            'maleCandidates' => $maleCandidates,
            'femaleCandidates' => $femaleCandidates,
            'microsoftOffice' => $microsoftOffice,
            'adobeSuite' => $adobeSuite,
            'bilingualCandidates' => $bilingualCandidates,
            'businessManagement' => $businessManagement,
            'eventParticipants' => $eventParticipants,
        ]);
    }
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
        $uniqueId = uniqid();
        $tempPdfPath = storage_path("app/temp_pdf_{$id}_{$uniqueId}.pdf");

        // Ensure the directory exists
        $directory = dirname($tempPdfPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Remove the file if it already exists
        if (file_exists($tempPdfPath)) {
            unlink($tempPdfPath);
        }

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
        $applicants = Applicant::where('published', true)->orderBy('created_at', 'desc')->get();
        return response()->json($this->processApplicantsData($applicants));
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

            $processedApplicants = $this->processApplicantsData($applicants);
            $query->orderBy('created_at', 'desc');
            $results = [
                'data' => $processedApplicants,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalResults,
                'last_page' => ceil($totalResults / $perPage)
            ];

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while searching applicants: ' . $e->getMessage()], 500);
        }
    }

    public function getFilteredApplicants(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 12);
            $query = Applicant::query()->where('published', true);

            $this->filterService->applyFilters($query, $request);
            $query->orderBy('created_at', 'desc');
            $results = $query->paginate($perPage);

            $results->setCollection($this->processApplicantsData($results->getCollection()));

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
        $processedApplicant = $this->processApplicantData($applicant->toArray());
        return response()->json($processedApplicant, 200);
    }

    public function getAuthUser()
    {
        $user = Auth::user()->load("applicant");
        return response()->json($user);
    }

    private function processApplicantData(array $applicantData): array
    {
        // First check if contact exists
        if (isset($applicantData['contact'])) {
            // Check if phone exists and is an array
            if (isset($applicantData['contact']['phone']) && is_array($applicantData['contact']['phone'])) {
                // Only modify if showPhone key exists and is false
                if (isset($applicantData['contact']['phone']['showPhone']) &&
                    !$applicantData['contact']['phone']['showPhone']) {
                    $applicantData['contact']['phone']['phone'] = '';
                }
            }
        }
        return $applicantData;
    }

// Helper method to process multiple applicants' data
    private function processApplicantsData($applicants)
    {
        return $applicants->map(function ($applicant) {
            return $this->processApplicantData($applicant->toArray());
        });
    }

    // Placeholder methods
    public function create()
    {
    }

    public function edit(Applicant $applicant)
    {
    }

    public function update(Request $request, Applicant $applicant)
    {
    }

    public function destroy(Applicant $applicant)
    {
    }
}
