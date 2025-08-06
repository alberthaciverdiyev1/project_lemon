<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $guarded = [];

    public function images(): HasMany
    {
        return $this->hasMany()
    }

}
