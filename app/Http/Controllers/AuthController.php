<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private UserService $service;

    function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request):JsonResponse
    {
        return $this->service->register($request);
    }

    public function login(Request $request):JsonResponse
    {
        return $this->service->login($request);
    }

    public function me():JsonResponse
    {
        return $this->service->me();
    }
    public function refreshToken()
    {
        return $this->service->refreshToken();
    }
    public function logout()
    {
        return $this->service->logout();
    }
}
