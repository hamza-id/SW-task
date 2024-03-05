<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponderHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Register;
use App\Http\Requests\Api\Auth\SignIn;
use Illuminate\Support\Facades\{Auth, Log, Validator};

class AuthController extends Controller
{
    protected $responder;
    public function __construct()
    {
        $this->responder = new ResponderHelper();
    }

    public function register(Register $request)
    {
        $data    = array();
        $error   = false;
        $message = 'User Registration Successfully!';

        try {
            $input = $request->all();
            $input['password']  = bcrypt($input['password']);
            $data               = User::create($input);
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }

    public function login(SignIn $request)
    {
        $data    = array();
        $error   = false;
        $message = 'Login Successfully!';

        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $data['token'] = auth()->user()->createToken('sw-task')->accessToken;
            } else
                $message = 'Email or password doesnot match!';
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }

    public function logout(Request $request)
    {
        $error   = false;
        $message = 'Successfully logged out!';
        $token = $request->user()->token();
        $token->revoke();
        return $this->responder->respond($error, [], $message);
    }

    public function user(Request $request)
    {
        $error   = false;
        $message = 'Successfully fetch record!';
        return $this->responder->respond($error, $request->user(), $message);
    }
}
