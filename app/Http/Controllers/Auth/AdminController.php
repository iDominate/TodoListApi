<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    use ResponseTrait;
    function login(AdminLoginRequest $request)
    {
        $admin = User::where([["role", "admin"], ["email", $request->email]])->first();
        
        if(!$admin || !($request->password == $admin->password))
        {
            return response($this->returnMessage(message: "Invalid Credentials", data: null), Response::HTTP_UNAUTHORIZED);
        }

        $data = [
            "user" => $admin,
            "token" => $admin->createToken("admin")->plainTextToken
        ];

        return response($this->returnMessage(message: "Login Success", data: $data), Response::HTTP_OK);
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response($this->returnMessage(message: "Logged Out Successfully", data: null), Response::HTTP_OK);
    }
}
