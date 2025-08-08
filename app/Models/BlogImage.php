<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BlogImage extends Model
{
    protected $table = 'blogs_images';
    protected $guarded = [];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
