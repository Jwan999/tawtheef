<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $applicant = new Applicant;
        dd($request);
        if ($request->hasFile('image')) {
            // Store image
            $path = $request->file('image')->store('images', 'public');
            $applicant->image = $path;
        }

        $applicant->speciality_title = json_decode($request->speciality, true)['specializations'];
        $applicant->speciality_children = json_decode($request->speciality, true)['children'];
        $applicant->education = json_decode($request->education, true);
        $applicant->languages = json_decode($request->languages, true);
        $applicant->skills = explode(',', $request->skills);
        $applicant->tools = json_decode($request->tools, true);
        $applicant->work_availability = $request->input('details.workAvailability');
        $applicant->full_name = $request->input('details.fullName');
        $applicant->summary = $request->input('summary');
        $applicant->courses = json_encode($request->input('courses'));
        $applicant->phone = $request->input('contact.phone');
        $applicant->gender = $request->input('contact.gender');
        $applicant->email = $request->input('contact.email');
        $applicant->links = json_encode($request->input('contact.links'));
        $applicant->birthdate = $request->input('contact.birthdate');
        $applicant->city = $request->input('contact.city');
        $applicant->zone = $request->input('contact.zone');
        $applicant->employment = json_encode($request->input('employment'));
        $applicant->activities = json_encode($request->input('activities'));
        $applicant->profileable_id = $request->input('profileable_id');
        $applicant->profileable_type = $request->input('profileable_type');

        $applicant->save();

        return response()->json($applicant, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Applicant $applicant, $id)
    {
        $userId = Auth::id();

        $applicant = Applicant::find($id);
        $applicant->user_id = $userId;

        return response()->json($applicant);
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
