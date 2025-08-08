<?php

namespace App\Http\Controllers;

use App\Http\Services\CompanyService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyController extends Controller
{
    private CompanyService $service;

    function __construct(CompanyService $service)
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
}
