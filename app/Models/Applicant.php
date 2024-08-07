<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Applicant extends Model
{

    const COLUMN_SUMMARY = 'summary';
    const COLUMN_TOOLS = 'tools';
    const COLUMN_EMPLOYMENT = 'employment';
    const COLUMN_SPECIALITY = 'speciality';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;

    protected $attributes = [
        'employment' => '[{"title":"","employer":"","duration":["Start year","End Year"],"responsibilities":[]}]',
        'courses' => '[{"title": "", "duration": "", "entity": ""}]',
        'activities' => '[{"title":"","participatedAs":"Participated as","year":"Year"}]',
        'education' => '[{"degree": "", "institute": "", "duration": ["Start Year", "End Year"]}]',
        'languages' => '[{"item":"","rating":""}]',
        'tools' => '[{"item":"","rating":""}]',
        'skills' => '[]',
        'summary' => '',
        'speciality' => '{"specializations": [], "children": []}',
    ];

    protected $casts = [
        'education' => "json",
        'employment' => "json",
        'languages' => "json",
        'courses' => "json",
        'skills' => "json",
        'activities' => "json",
        'contact' => "json",
        'details' => 'json',
        'tools' => "json",
        'speciality' => "json"
    ];
    protected $fillable = [
        'education',
        'summary',
        'employment',
        'languages',
        'courses',
        'skills',
        'activities',
        'contact',
        'details',
        'tools',
        'speciality',
    ];
}
