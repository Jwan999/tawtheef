<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'links',
        'education',
        'job_status',
        'summary',
        'employment',
        'languages',
        'courses',
        'skills',
        'activities',
    ];
}
