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

    // Helper method to process applicant data
    private function processApplicantData(array $applicantData): array
    {
        if (isset($applicantData['contact']['showPhone'])) {
            if (!$applicantData['contact']['showPhone']) {
                unset($applicantData['contact']['phone']);
            }
            unset($applicantData['contact']['showPhone']);
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
    public function create() {}
    public function edit(Applicant $applicant) {}
    public function update(Request $request, Applicant $applicant) {}
    public function destroy(Applicant $applicant) {}
}
