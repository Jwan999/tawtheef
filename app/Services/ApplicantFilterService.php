<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicantFilterService
{
    protected $isPostgres;

    public function __construct()
    {
        $this->isPostgres = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql';
    }

    public function applyFilters(Builder $query, Request $request): void
    {
        $this->filterGender($query, $request);
        $this->filterCity($query, $request);
        $this->filterAgeRange($query, $request);
        $this->filterDegree($query, $request);
        $this->filterFreshGraduate($query, $request);
        $this->filterWorkAvailability($query, $request);
        $this->filterExperience($query, $request);
        $this->filterMainSpecializations($query, $request);
        $this->filterSubSpecialities($query, $request);
    }

    protected function filterGender(Builder $query, Request $request): void
    {
        ;
        if ($request->filled('gender')) {
            $genderValue = strtolower($request->input('gender'));
            if ($this->isPostgres) {
                $query->whereRaw("LOWER(contact->>'gender') LIKE ?", [$genderValue]);
//                dump(count($query->get()));
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.gender'))) LIKE ?", [$genderValue]);
            }
        }
    }

    protected function filterCity(Builder $query, Request $request): void
    {
        if ($request->filled('city')) {
            $cityValue = strtolower($request->input('city'));
            if ($this->isPostgres) {
                $query->whereRaw("LOWER(contact->>'city') ILIKE ?", [$cityValue]);
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.city'))) LIKE ?", [$cityValue]);
            }

            if (strtolower($request->input('city')) === 'baghdad' && $request->filled('zone')) {
                $zoneValue = strtolower($request->input('zone'));
                if ($this->isPostgres) {
                    $query->whereRaw("LOWER(contact->>'zone') ILIKE ?", [$zoneValue]);
                } else {
                    $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.zone'))) LIKE ?", [$zoneValue]);
                }
            }
        }
    }

    protected function filterAgeRange(Builder $query, Request $request): void
    {
        if ($request->filled('age') && is_array($request->input('age')) && count($request->input('age')) == 2) {
            $ageRange = $request->input('age');
            if ($this->isPostgres) {
                $query->whereRaw(
                    "CASE WHEN contact->>'birthdate' ~ '^\d{4}-\d{2}-\d{2}$' THEN DATE_PART('year', AGE(CURRENT_DATE, (contact->>'birthdate')::DATE)) ELSE NULL END BETWEEN ? AND ?",
                    [min($ageRange), max($ageRange)]
                );
            } else {
                $query->whereRaw(
                    "CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(contact, '$.birthdate')) REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN YEAR(CURDATE()) - YEAR(STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.birthdate')), '%Y-%m-%d')) ELSE NULL END BETWEEN ? AND ?",
                    [min($ageRange), max($ageRange)]
                );
            }
        }
    }

    protected function filterDegree(Builder $query, Request $request): void
    {
        if ($request->filled('degree')) {
            $degreeValue = strtolower($request->input('degree'));
            if ($this->isPostgres) {
                $query->whereRaw("education::jsonb @> ?::jsonb", [json_encode([['degree' => $degreeValue]])]);
            } else {
                $query->whereRaw("JSON_CONTAINS(LOWER(education), JSON_ARRAY(?))", [$degreeValue]);
            }
        }
    }

    protected function filterFreshGraduate(Builder $query, Request $request): void
    {
        if ($request->filled('freshGraduate')) {
            $isFreshGraduate = filter_var($request->input('freshGraduate'), FILTER_VALIDATE_BOOLEAN);
            if ($isFreshGraduate) {
                $twoYearsAgo = now()->subYears(2)->startOfYear()->year;
                $currentYear = now()->year;
                if ($this->isPostgres) {
                    $query->whereRaw(
                        "education::jsonb @> ?::jsonb AND (education->0->>'degree')::text ILIKE ? AND (education->0->'duration'->1)::int BETWEEN ? AND ?",
                        [json_encode([['degree' => "Bachelor's Degree"]]), strtolower("Bachelor's Degree"), $twoYearsAgo, $currentYear]
                    );
                } else {
                    $query->whereRaw(
                        "JSON_CONTAINS(education, ?, '$') AND LOWER(JSON_UNQUOTE(JSON_EXTRACT(education, '$[0].degree'))) = ? AND CAST(JSON_UNQUOTE(JSON_EXTRACT(education, '$[0].duration[1]')) AS UNSIGNED) BETWEEN ? AND ?",
                        [json_encode(['degree' => "Bachelor's Degree"]), strtolower("Bachelor's Degree"), $twoYearsAgo, $currentYear]
                    );
                }
            }
        }
    }

    protected function filterWorkAvailability(Builder $query, Request $request): void
    {
        if ($request->filled('workAvailability')) {
            $isAvailable = filter_var($request->input('workAvailability'), FILTER_VALIDATE_BOOLEAN);
            if ($this->isPostgres) {
                $query->whereRaw("(contact->>'workAvailability')::boolean = ?", [$isAvailable]);
            } else {
                $query->where(function ($q) use ($isAvailable) {
                    $trueValues = ['true', '1', 1, true];
                    $falseValues = ['false', '0', 0, false, null];
                    $compareValues = $isAvailable ? $trueValues : $falseValues;

                    foreach ($compareValues as $value) {
                        $q->orWhereRaw(
                            "JSON_UNQUOTE(JSON_EXTRACT(contact, '$.workAvailability')) <=> ?",
                            [$value]
                        );
                    }
                });
            }
        }
    }

    protected function filterExperience(Builder $query, Request $request): void
    {
        if ($request->filled('experience') && is_array($request->input('experience')) && count($request->input('experience')) == 2) {
            $experienceRange = $request->input('experience');
            if ($this->isPostgres) {
                $query->whereRaw(
                    "COALESCE((SELECT SUM(CASE WHEN (job->>'duration')::json->>1 = 'present' THEN EXTRACT(YEAR FROM CURRENT_DATE) - ((job->>'duration')::json->>0)::int ELSE ((job->>'duration')::json->>1)::int - ((job->>'duration')::json->>0)::int END) FROM jsonb_array_elements(employment::jsonb) AS job), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            } else {
                $query->whereRaw(
                    "COALESCE((SELECT SUM(CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[1]')) = 'present' THEN YEAR(CURDATE()) - CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[0]')) AS UNSIGNED) ELSE CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[1]')) AS UNSIGNED) - CAST(JSON_UNQUOTE(JSON_EXTRACT(job, '$.duration[0]')) AS UNSIGNED) END) FROM JSON_TABLE(employment, '$[*]' COLUMNS (job JSON PATH '$')) AS jobs), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            }
        }
    }

    protected function filterMainSpecializations(Builder $query, Request $request): void
    {
        if ($request->filled('mainSpecializations') && is_array($request->input('mainSpecializations'))) {
            $mainSpecializations = $request->input('mainSpecializations');
            if ($this->isPostgres) {
                $query->whereIn(DB::raw("speciality->>'parent'"), $mainSpecializations);
            } else {
                $query->whereIn(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(speciality, '$.parent'))"), $mainSpecializations);
            }
        }
    }

    protected function filterSubSpecialities(Builder $query, Request $request): void
    {
        if ($request->filled('subSpecialities') && is_array($request->input('subSpecialities'))) {
            $subSpecialities = $request->input('subSpecialities');
            if ($this->isPostgres) {
                $query->whereRaw(
                    "speciality->>'children' ?| array[" . implode(',', array_fill(0, count($subSpecialities), '?')) . "]",
                    $subSpecialities
                );
            } else {
                $query->whereRaw(
                    "JSON_OVERLAPS(JSON_EXTRACT(speciality, '$.children'), JSON_ARRAY(" . implode(',', array_fill(0, count($subSpecialities), '?')) . "))",
                    $subSpecialities
                );
            }
        }
    }
}
