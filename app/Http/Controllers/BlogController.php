<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Blog\BlogDetailsResource;
use App\Http\Transformers\Blog\BlogListResource;
use App\Models\Blog;
use App\Models\BlogImage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;

class BlogController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        $blogs = Blog::query()
            ->select('title', 'slug', 'description', 'created_at')
            ->latest()
            ->get();


        return BlogListResource::collection($blogs);
    }


    public function details(string $slug)
    {

        $blog = Blog::query()
            ->select(['id', 'title', 'slug', 'description'])
            ->where('slug', $slug)
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => 400,
                'message' => __('Blog not found!')
            ]);
        }

        return BlogDetailsResource::make($blog);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string',
            'description' => 'required|string',
            'images.*'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $validatedData = $validator->validated();

            $validatedData['slug'] = Str::slug($validatedData['title']) . '-' . rand(10, 1000000);

            $blog = Blog::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'slug' => $validatedData['slug'],
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('blogs', 'public');
                    $blog->images()->create(['image_url' => $path]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Blog created successfully.',
                'status_code' => 201
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(string $slug, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'images.*'    => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();

        try {
            $validatedData = $validator->validated();

            $blog = Blog::where('slug', $slug)->firstOrFail();

            if (isset($validatedData['title'])) {
                $validatedData['slug'] = Str::slug($validatedData['title']) . '-' . rand(10, 1000000);
            }

            $blog->update($validatedData);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('blogs', 'public');
                    $blog->images()->create(['image_url' => $path]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Blog updated successfully.',
                'status_code' => 200
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $slug): JsonResponse
    {
        try {
            $blog = Blog::where('slug', $slug)->first();

            if (!$blog) {
                return response()->json([
                    'message' => 'Blog not found.',
                    'status_code' => 404
                ]);
            }

            BlogImage::where('blog_id', $blog->id)->delete();

            $blog->delete();

            return response()->json([
                'message' => 'Blog deleted successfully.',
                'status_code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
