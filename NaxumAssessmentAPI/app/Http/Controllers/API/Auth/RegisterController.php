<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\BaseController;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
   


    protected function create(Request $request)
    {

        $messages = [
            'password.regex' => 'Password must be more than 8 characters long, should contain at least 1 Uppercase, 1 Lowercase and  1 number',
        ];
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'contactNumber' => ['required', 'min:8', 'unique:users,contactNumber'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/'],
        ], $messages);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->messages());
        $validated = $validator->validated();
        $emailExist = DB::table('users')->where(['email'=>$request->email])->exists();
        if ($emailExist){
            return $this->sendError('Email has already been taken', ['error' => ['Email has already been taken']]);
        }
        //check if username already exists
        $usernameExist = DB::table('users')->where(['username'=>$request->username])->exists();
        if ($usernameExist){
            return $this->sendError('Username has already been taken', ['error' => ['Username has already been taken']]);
        }


        $message = "Successfully Registered";

        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'username' => $validated['username'],
            'contactNumber' => $validated['contactNumber'],
            'password' => Hash::make($validated['password']),
        ]);
        $data['email'] = $user->email;
        $data['username'] = $user->username;        

        return $this->sendResponse($data, $message, Response::HTTP_CREATED);

    }

}