<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller {

    public function create(Request $request) {
        $request->validate([
            'email' => 'unique:users|email',
            'password' => 'confirmed',
        ]);

        $user = new UserController();

        return $user->store($request);
    }
}
