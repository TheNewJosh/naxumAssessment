<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Classes\CommonClass;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * @var CommonClass
     */
    private $common;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->messages());
        }

        $validated = $validator->validated();

        if (
            Auth::attempt(['email' => $validated['userId'], 'password' => $validated['password']]) ||
            Auth::attempt(['username' => $validated['userId'], 'password' => $validated['password']])
        ) {
            $user = Auth::user();
            
            $loginToken = $user->createToken('user');
            //return $loginToken->accessToken->id;
            $data['accessToken'] = $loginToken->plainTextToken;
            $data['username'] = $user->username;
            $data['email'] = $user->email;
            $message = 'Login successful';

            return $this->sendResponse($data, $message, Response::HTTP_CREATED);
        }

        return $this->sendError('Your user id or password is incorrect', ['error' => ['Your user id  or password is incorrect']]);


    }

}
