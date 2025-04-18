<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'company',
        'job_title',
        'phone',
        'email',
        'country_code',
    ];
}
