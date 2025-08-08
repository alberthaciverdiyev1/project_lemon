<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }


}
