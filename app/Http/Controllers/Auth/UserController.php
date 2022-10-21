<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    
    use ResponseTrait;

    function register(UserRegisterRequest $request)
    {
        $user = User::create($request->validated());
        return response($this->returnMessage(message: "User Created", data: $user), Response::HTTP_CREATED);
    }

    function login(UserLoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();

        
        if(!$user || !($request->password == $user->password))
        {
            return response($this->returnMessage(message: "Invalid Credentials", data: null), Response::HTTP_UNAUTHORIZED);
        }

        $data = [
            "user" => $user,
            "token" => $user->createToken("user")->plainTextToken
        ];

        return response($this->returnMessage(message: "Login Success", data: $data), Response::HTTP_OK);
    }

    function profile()
    {
        return response($this->returnMessage(message: "My Profile", data: auth()->user()), Response::HTTP_OK);
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response($this->returnMessage(message: "Logged Out Successfully"), Response::HTTP_OK);
    }
}
