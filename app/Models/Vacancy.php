<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Vacancy extends Model
{
    protected $table = 'vacancies';
    protected $guarded = [];

}
