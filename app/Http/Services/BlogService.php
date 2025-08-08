<?php

namespace App\Http\Services;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Response;

class BlogService
{
    private Blog $model;

    function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }


    public function add(?Request $request = null, array $data = []): JsonResponse
    {
        if ($request instanceof Request) {
            $data = $request->only(['name', 'slug', 'description']);
        }

        if (empty($data)) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Data is empty'
            ], Response::HTTP_BAD_REQUEST);
        }

        $array = [
            'title'       => $data['name'] ?? '',
            'slug'        => $data['slug'] ?? '',
            'description' => $data['description'] ?? '',
        ];

        Log::info($array);

        $this->model->setConnection('pgsql')->create($array);

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => 'success'
        ], Response::HTTP_OK);
    }


}
