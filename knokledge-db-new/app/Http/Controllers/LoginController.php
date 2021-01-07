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

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json(Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }

    public function emulateUser($id): \Illuminate\Http\JsonResponse {
       if (!Auth::check()) {
           return response()->json(['message' => 'No authenticated user']);
        }

        if (Auth::user()->role != "admin") {
            return response()->json(['message' => ['Only for admins']]);
        }

        session_start();
        $_SESSION['adminId'] = Auth::id();
        if (Auth::loginUsingId($id)) {
            return response()->json(Auth::user());
        }
        return response()->json(['message' => ['Unexpected exception']]);
    }


    public function cancelEmulation(): \Illuminate\Http\JsonResponse {
        session_start();
        if (Auth::loginUsingId($_SESSION['adminId'])) {
            unset($_SESSION['adminId']);
            session_destroy();
            return response()->json(Auth::user());
        }
        return response()->json(['message' => ['Unexpected exception']]);
    }

    public function logout() {

        Auth::logout();
    }
}
