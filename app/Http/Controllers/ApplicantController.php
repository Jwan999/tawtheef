<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $applicants = Applicant::all();
        return response()->json($applicants);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        $applicant = new Applicant;
////        dd($request);
//        if ($request->hasFile('image')) {
//            // Store image
//            $path = $request->file('image')->store('images', 'public');
//            $applicant->image = $path;
//        }
//
//        $applicant->speciality_title = json_decode($request->speciality, true)['specializations'];
//        $applicant->speciality_children = json_decode($request->speciality, true)['children'];
//        $applicant->education = json_decode($request->education, true);
//        $applicant->languages = json_decode($request->languages, true);
//        $applicant->skills = explode(',', $request->skills);
//        $applicant->tools = json_decode($request->tools, true);
//        $applicant->work_availability = $request->input('details.workAvailability');
//        $applicant->full_name = $request->input('details.fullName');
//        $applicant->summary = $request->input('summary');
//        $applicant->courses = json_encode($request->input('courses'));
//        $applicant->phone = $request->input('contact.phone');
//        $applicant->gender = $request->input('contact.gender');
//        $applicant->email = $request->input('contact.email');
//        $applicant->links = json_encode($request->input('contact.links'));
//        $applicant->birthdate = $request->input('contact.birthdate');
//        $applicant->city = $request->input('contact.city');
//        $applicant->zone = $request->input('contact.zone');
//        $applicant->employment = json_encode($request->input('employment'));
//        $applicant->activities = json_encode($request->input('activities'));
//        $applicant->profileable_id = $request->input('profileable_id');
//        $applicant->profileable_type = $request->input('profileable_type');
//
//        $applicant->save();
//
//        return response()->json($applicant, 201);
//    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Parse the JSON data
        $requestData = json_decode($request->input('data'), true);

        // Additional validation for parsed JSON data
        $validator = Validator::make($requestData, [
            'speciality' => 'required|array',
            'education' => 'required|array',
            'languages' => 'required|array',
            'skills' => 'required|array',
            'tools' => 'required|array',
            'details' => 'required|array',
            'summary' => 'required|string',
            'courses' => 'required|array',
            'contact' => 'required|array',
            'employment' => 'required|array',
            'activities' => 'required|array',
            'published' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle the image file if it's present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('applicant_images', 'public');
        }

        // Create a new applicant instance and save the data
        $applicant = new Applicant();
        $applicant->image = $imagePath;
        $applicant->speciality = json_encode($requestData['speciality']);
        $applicant->education = json_encode($requestData['education']);
        $applicant->languages = json_encode($requestData['languages']);
        $applicant->skills = json_encode($requestData['skills']);
        $applicant->tools = json_encode($requestData['tools']);
        $applicant->details = json_encode($requestData['details']);
        $applicant->summary = $requestData['summary'];
        $applicant->courses = json_encode($requestData['courses']);
        $applicant->contact = json_encode($requestData['contact']);
        $applicant->employment = json_encode($requestData['employment']);
        $applicant->activities = json_encode($requestData['activities']);
        $applicant->published = $requestData['published'];

        $applicant->save();

        return response()->json(['message' => 'Applicant data saved successfully', 'data' => $applicant], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retrieve the applicant data by ID
        $applicant = Applicant::with('user')->find($id);

        // Check if the applicant exists
        if (!$applicant) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        // Ensure the authenticated user can only access their own data
        if (Auth::id() !== $applicant->id) { // Assuming you have a user_id column in applicants table
            return response()->json(['message' => 'Unauthorized'], 403);
        }
//        dd($applicant);
        // Return the applicant data as JSON
        return response()->json($applicant, 200);
    }


    public function getAuthUserId()
    {
        $user = Auth::user();
        return response()->json($user);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Applicant $applicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Applicant $applicant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Applicant $applicant)
    {
        //
    }
}
