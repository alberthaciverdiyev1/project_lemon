<?php

namespace App\Http\Services;

use App\Helpers\Enum;
use App\Http\Enums\EducationLevel;
use App\Http\Enums\JobType;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class VacancyService
{
    private Vacancy $model;

    public function __construct(Vacancy $model)
    {
        $this->model = $model;
    }

    public function list($request)
    {
        $page = max((int)$request->query('page', 1), 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = $this->model->count();

        $vacancies = $this->model->skip($offset)->take($perPage)->get();

        return response()->json([
            'data' => $vacancies,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
            ],
        ]);
    }
    public function store($request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'company_id'        => 'required|exists:companies,id',
            'category_id'       => 'required|exists:categories,id',
            'city_id'           => 'required|exists:cities,id',
            'salary_min'        => 'nullable|string',
            'salary_max'        => 'nullable|string',
            'experience'        => 'nullable|string',
            'email'             => 'nullable|email',
            'responsible_person'=> 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:50',
            'education_level'   => 'nullable|string',
            'experience_level'  => 'nullable|string',
            'job_type'          => 'nullable|string',
            'age_min'           => 'nullable|integer|min:0',
            'age_max'           => 'nullable|integer|min:0',
            'user_id'           => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
        ]);

        $slug = Str::slug($validated['title']) . '-' . uniqid('', true);

        $vacancy = Vacancy::create([
            'slug'              => $slug,
            'title'             => $validated['title'],
            'description'       => $validated['description'],
            'company_id'        => $validated['company_id'],
            'category_id'       => $validated['category_id'],
            'city_id'           => $validated['city_id'],
            'salary_min'        => $validated['salary_min'] ?? null,
            'salary_max'        => $validated['salary_max'] ?? null,
            'experience'        => $validated['experience'] ?? null,
            'email'             => $validated['email'] ?? null,
            'responsible_person'=> $validated['responsible_person'] ?? null,
            'phone'             => $validated['phone'] ?? null,
            'education_level'   => $validated['education_level'] ? Enum::check(EducationLevel::class, $validated['education_level']) : Enum::check(EducationLevel::class, 'Bachelor'),
            'experience_level'  => $validated['experience_level'] ? Enum::check(EducationLevel::class, $validated['experience_level']) : Enum::check(EducationLevel::class, 'Junior'),
            'job_type'          => $validated['job_type'] ? Enum::check(JobType::class, $validated['job_type']) : Enum::check(JobType::class, 'Full-time'),
            'age_min'           => $validated['age_min'] ?? 18,
            'age_max'           => $validated['age_max'] ?? 70,
            'user_id'           => $validated['user_id'] ?? null,
            'is_active'         => $validated['is_active'] ?? false,
        ]);

        return response()->json([
            'message' => 'Vacancy created successfully.',
            'data' => $vacancy
        ], 201);
    }

}
