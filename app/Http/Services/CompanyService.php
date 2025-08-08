<?php

namespace App\Http\Services;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CompanyService
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:companies,email',
            'password'           => 'required|string|min:6|confirmed',
            'mail_for_send_resume' => 'required|email|unique:companies,mail_for_send_resume',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $company = Company::create([
            'name'               => $request->name,
            'slug'               => Str::slug($request->name) . '-' . rand(1000, 9999),
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'mail_for_send_resume' => $request->mail_for_send_resume
        ]);

        $token = auth('companies')->login($company);

        return response()->json([
            'message' => 'Company registered successfully',
            'token'   => $token,
            'company' => $company,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('companies')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token'   => $token,
            'company' => auth('companies')->user(),
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('companies')->user());
    }

    public function refreshToken(): JsonResponse
    {
        try {
            $newToken = auth('companies')->refresh();

            return response()->json([
                'access_token' => $newToken,
                'token_type'   => 'bearer',
                'expires_in'   => auth('companies')->factory()->getTTL() * 60
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'error' => 'Invalid token'
            ], 401);
        }
    }
}
