<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validate login credentials
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login using Laravel Auth
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Login successful
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            // Login failed
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
    }

    public function register(Request $request)
    {

        // Validate signup data
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
            'profileType' => 'required',
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->profile_type = $request->profileType;
        $user->password = bcrypt($request->password);
//        dd($user);

        $user->save();
        Auth::login($user);


        return response()->json(['message' => 'Signup successful'], 201);
    }
}
