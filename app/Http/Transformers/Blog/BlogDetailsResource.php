<?php

namespace App\Http\Transformers\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => null,
            'readTime' => ceil(str_word_count(strip_tags($this->description)) / 200),
            'createdAt' => $this->created_at->format('d.m.Y'),
        ];
    }
}
