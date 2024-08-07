<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function user()
    {
        return $this->morphOne(User::class, 'profileable'); // Reverse of the relationship
    }

    protected $fillable = [
        'email',
        'password',
        // Additional company attributes (e.g., company name)
    ];
    use HasFactory;
}
