<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CompanyImage extends Model
{
    protected $table = 'company_images';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
