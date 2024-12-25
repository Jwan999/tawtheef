<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function login(Request $request)
    {
        // Validate login credentials
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'recaptcha_token' => 'required|string',
        ]);

        // Attempt login using Laravel Auth
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            return response()->json([
                'message' => 'Login successful',
                'user' => $user->load('applicant'),
                'isAdmin' => $user->role === 'admin',
            ], 200);
        } else {
            // Login failed
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
    }

    public function signup(Request $request)
    {
        // Validate signup data
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
            'profile_type' => 'required|in:Applicant,Company',
            'recaptcha_token' => 'required|string',
        ]);

        try {
            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            $user->role = 'user'; // Explicitly set role as user for new registrations
            $user->save();

            // If the profile type is Applicant, create an applicant record
            if ($request->profile_type === 'Applicant') {
                $applicant = new Applicant();
                $applicant->user_id = $user->id;

                // Initialize all required JSON fields with empty default structures
                $applicant->employment = [["title" => "", "employer" => "", "duration" => ["Start year", "End Year"], "responsibilities" => []]];
                $applicant->courses = [["title" => "", "duration" => "", "entity" => ""]];
                $applicant->activities = [["title" => "", "participatedAs" => "Participated as", "year" => "Year"]];
                $applicant->education = [["degree" => "", "institute" => "", "duration" => ["Start Year", "End Year"]]];
                $applicant->languages = [["item" => "", "rating" => ""]];
                $applicant->tools = [["item" => "", "rating" => ""]];
                $applicant->skills = [];
                $applicant->summary = ""; // This is a text field, not JSON
                $applicant->speciality = ["specializations" => [], "children" => []];
                $applicant->contact = []; // Add the required contact field
                $applicant->image = null; // Nullable string field
                $applicant->details = null; // Nullable JSON field
                $applicant->published = false; // Boolean field with default false

                $applicant->save();
            }

            Auth::login($user);

            return response()->json([
                'message' => 'Signup successful',
                'user' => $user->load('applicant'),
                'isAdmin' => false,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Signup error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during signup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        // Validate signup data
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
            'recaptcha_token' => 'required|string',
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->role = 'user'; // Explicitly set role as user for new registrations
        $user->save();

        Auth::login($user);

        return response()->json([
            'message' => 'Signup successful',
            'user' => $user->load('applicant'),
            'isAdmin' => false,
        ], 201);
    }

    public function checkAdminStatus()
    {
        if (!Auth::check()) {
            return response()->json(['isAdmin' => false], 401);
        }

        return response()->json([
            'isAdmin' => Auth::user()->role === 'admin',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email'])
            : response()->json(['message' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        return response()->json(['message' => trans($status)], 400);
    }}
