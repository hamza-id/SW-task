<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        $input['password']  = bcrypt($input['password']);
        $user               = User::create($input);

        $data['token']      = $user->createToken('sw-task')->accessToken;
        $data['message']    = "User Registration Successfully!";
        return response()->json(['data' => $data], 200);
    }

    public function login(Request $request)
    {
        $data = array();
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $data['token']   = auth()->user()->createToken('sw-task')->accessToken;
            $data['massage'] = "Login Successfully!";
            return response()->json(['token' => $data], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
