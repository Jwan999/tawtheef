<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\User;
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
            $applicant->details = $requestData['details'];
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
            $applicant->details = $requestData['details'];
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


    /**
     * Display the specified resource.
     */
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
