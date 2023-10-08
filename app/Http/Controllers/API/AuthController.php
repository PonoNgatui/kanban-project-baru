<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => ['required', 'email', 'unique:users'],
                'password' => 'required',
            ],
            [
                'email.unique' => 'The email address is already taken.',
            ],
            $request->all()
        );

        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('AuthToken');
        $user->token=$token->plainTextToken;

        return response()->json([
            'code'=>201,
            'message'=>'Sign Up Success',
            'data'=>$user,
        ]);
    }

    public function login(Request $request)
    {
    $request->validate(
        [
            'email' => ['required', 'email'],
            'password' => 'required',
        ],
        $request->all()
    );

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'code'=>Response::HTTP_UNAUTHORIZED,
            'message'=>'These credentials do not match our records.',
        ],Response::HTTP_UNAUTHORIZED);
    }
    
    $user=$request->user();
    $token = $user->createToken('AuthToken');
    $user->token=$token->plainTextToken;

    return response()->json([
        'code'=>Response::HTTP_OK,
        'message'=>'Login Success',
        'data'=>$user,
    ],Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'message' => 'Logout success!',

        ]);
    }
}
