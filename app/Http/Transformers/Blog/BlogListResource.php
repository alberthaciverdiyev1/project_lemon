<?php

namespace App\Http\Transformers\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'shortDescription' => Str::limit(strip_tags($this->description), 150),
            'readTime' => ceil(str_word_count(strip_tags($this->description)) / 200),
            'createdAt' => $this->created_at->format('d.m.Y'),
        ];    }
}
