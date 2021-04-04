<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthUser;
use App\Services\User\UserInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private $user_service;

    public function __construct(UserInterface $user_service)
    {
        $this->user_service = $user_service;
    }

    public function authenticate(AuthUser $request)
    {
        return $this->jsonResponse($this->user_service->authenticate($request->all()));
    }

    public function error(Request $request)
    {
        if (!Auth::check()) {
            throw new AuthenticationException(Message::$error_unauthorized);
        }
    }
}
