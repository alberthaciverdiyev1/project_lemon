<?php

namespace App\Http\Controllers;

use App\Http\Services\VacancyService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class VacancyController extends Controller
{
    private VacancyService $service;

    function __construct(VacancyService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }
    public function list(Request $request)
    {
        return $this->service->list($request);
    }
    public function update()
    {
        return "update";
    }
    public function delete()
    {
        return "delete";
    }
}
