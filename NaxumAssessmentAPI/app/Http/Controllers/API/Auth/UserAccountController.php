<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends BaseController
{
    public $search;

    public function list()
    {
        $data['users'] = User::get();
        $message = "User List";

        return $this->sendResponse($data, $message, Response::HTTP_CREATED);
    }
    
    public function search(Request $request)
    {
        $this->search = $request->user_search;

        $data['result'] = User::where(function($query)
            {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('contactNumber', 'like', '%' . $this->search . '%');
            })->get();

        $message = "Search Result";

        return $this->sendResponse($data, $message, Response::HTTP_CREATED);

    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.auth()->user()->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->user()->id],
            'contactNumber' => ['required', 'min:8', 'unique:users,contactNumber,'.auth()->user()->id],
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->messages());

        $message = "Successfully Updated";

        $user = User::where('id',auth()->user()->id)->update([
            'email' => $request->email,
            'name' => $request->name,
            'username' => $request->username,
            'contactNumber' => $request->contactNumber,
        ]);
        $data['email'] = $request->email;
        $data['username'] = $request->username;        

        return $this->sendResponse($data, $message, Response::HTTP_CREATED);
    }
}
