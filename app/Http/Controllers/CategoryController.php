<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Category\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;

class CategoryController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'image', 'parent_id'])
            ->latest()
            ->get();

        return CategoryResource::collection($categories);
    }

    public function details(string $slug)
    {
        $category = Category::query()
            ->select(['id', 'name', 'slug', 'image', 'parent_id'])
            ->where('slug', $slug)
            ->first();

        if (!$category) {
            return response()->json([
                'success' => 400,
                'message' => __('Category not found!')
            ]);
        }

        return CategoryResource::make($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();

            $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . rand(10, 1000000);

            if ($request->hasFile('images')) {
                $validatedData['images'] = $request->file('images')->store('categories', 'public');
            }

            Category::query()->create($validatedData);

            return response()->json([
                'message' => 'Category created successfully.',
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
            'parent_id' => 'sometimes|nullable|exists:categories,id',
            'images' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();

            if (isset($validatedData['name'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . rand(10, 1000000);
            }

            if ($request->hasFile('images')) {
                $validatedData['images'] = $request->file('images')->store('categories', 'public');
            }

            $updated = Category::query()->where('slug', $slug)->update($validatedData);

            if ($updated) {
                return response()->json([
                    'message' => 'Category updated successfully.',
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Category not found or no changes detected.',
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
            $deleted = Category::query()->where('slug', $slug)->delete();

            if ($deleted) {
                return response()->json([
                    'message' => 'Category deleted successfully.',
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Category not found.',
                    'status_code' => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
