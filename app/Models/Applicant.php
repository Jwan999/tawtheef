<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Applicant extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;

    protected $fillable = [
        'email',
        'password',
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
