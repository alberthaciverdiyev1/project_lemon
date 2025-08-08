<?php

namespace App\Http\Controllers;

use App\Http\Transformers\BaseResource;
use App\Models\Category;
use App\Models\City;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;

class CityController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        $categories = City::query()
            ->select(['name', 'slug'])
            ->latest()
            ->get();

        return BaseResource::collection($categories);
    }

    public function details(string $slug)
    {
        $category = City::query()
            ->select([ 'name', 'slug'])
            ->where('slug', $slug)
            ->first();

        if (!$category) {
            return response()->json([
                'success' => 400,
                'message' => __('City not found!')
            ]);
        }

        return BaseResource::make($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();

            $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . rand(10, 1000000);


            City::query()->create($validatedData);

            return response()->json([
                'message' => 'City created successfully.',
                'status_code' => 201
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $slug, \Illuminate\Http\Request $request): JsonResponse
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();

            if (isset($validatedData['name'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . rand(10, 1000000);
            }

            $updated = City::query()->where('slug', $slug)->update($validatedData);

            if ($updated) {
                return response()->json([
                    'message' => 'City updated successfully.',
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'City not found or no changes detected.',
                    'status_code' => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $deleted = City::query()->where('slug', $slug)->delete();

            if ($deleted) {
                return response()->json([
                    'message' => 'City deleted successfully.',
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'City not found.',
                    'status_code' => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
