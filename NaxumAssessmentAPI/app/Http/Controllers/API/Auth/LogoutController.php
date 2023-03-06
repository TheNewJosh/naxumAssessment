<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\BaseController;

class LogoutController extends BaseController
{
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $data = "";
        $message = 'Logout successful';

        return $this->sendResponse($data, $message, Response::HTTP_CREATED);
    }
}
