<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest,RegisterRequest};
use App\Models\User;
use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Auth,Password};

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request){
        $user = User::create($request->all());
        $response = new Response('API response');

        Auth::login($user);

        $token = $user->createToken('access_token')->plainTextToken;

        $fileName = 'user-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $user->storeImage($request->file('image')->storeAs('users/images', $fileName, 'public'));

        $response ->setContent(['message' => 'User created Successfully.','token' => $token]);
        $response->setStatusCode(201);
        return $response;});
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $response = new Response('API response');

        if (! Auth::attempt($credentials)) {

            return response()->json(['message' => 'your provided credentials cannot be verified.'], 401);
        }
        $user = Auth::user();

        $token = $user->createToken('access_token')->plainTextToken;

        $response->setContent([
            'message' => 'User logged in successfully.',
            'access_token' => $token,
        ]);
        return $response;
    }

    public function logout()
    {
        //dd(Auth::user()->getAuthIdentifier());
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
