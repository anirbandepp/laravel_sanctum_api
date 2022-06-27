<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'tc' => 'required'
        ]);

        if (User::where('email', '=', $r->email)->first()) {
            return response()->json([
                'msg' => 'Email already exists',
                'status' => 'failed',
            ]);
        }

        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
            'tc' => $r->tc
        ]);

        $token = $user->createToken($r->email)->plainTextToken;

        return response()->json([
            'msg' => 'Registration Success',
            'status' => 'success',
            'token' => $token
        ], 200);
    }

    public function login(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', '=', $r->email)->first();

        if ($user !== null) {

            if ($user->email === $r->email && Hash::check($r->password, $user->password)) {

                $token = $user->createToken($r->email)->plainTextToken;

                return response()->json([
                    'msg' => 'Login Success',
                    'status' => 'success',
                    'token' => $token
                ], 200);
            }
        } else {
            return response()->json([
                'msg' => 'Provided credentials are incorrect. Please try again',
                'status' => 'failed',
            ], 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'msg' => 'Logout Successfully',
            'status' => 'success',
        ], 200);
    }

    public function user_info()
    {
        $user = auth()->user();

        return response()->json([
            'msg' => 'Logged user information',
            'user' => $user,
            'status' => 'success',
        ], 200);
    }

    public function change_password(Request $r)
    {
        $r->validate([
            'password' => 'required|confirmed'
        ]);

        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($r->password);
        $loggeduser->update();

        return response()->json([
            'msg' => 'Password changed successfully',
            'status' => 'success',
        ], 200);
    }
}
