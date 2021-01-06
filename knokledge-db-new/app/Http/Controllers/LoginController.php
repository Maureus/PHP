<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse {

        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))){
            return response()->json(Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }

    public function emulateUser($id): \Illuminate\Http\JsonResponse {
       if (Auth::loginUsingId($id)){
           return response()->json(Auth::user());
       }

       throw ValidationException::withMessages([
           'email' => ['The provided credentials are incorrect.']
       ]);
    }

    public function logout() {

        Auth::logout();
    }
}
