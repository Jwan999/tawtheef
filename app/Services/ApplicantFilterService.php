<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicantFilterService
{
    protected $isPostgres;

    public function __construct()
    {
        $this->isPostgres = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql';
    }

    public function applyFilters(Builder $query, Request $request): void
    {
        try {
            $this->filterGender($query, $request);
            $this->filterCity($query, $request);
            $this->filterAgeRange($query, $request);
            $this->filterDegree($query, $request);
            $this->filterFreshGraduate($query, $request);
            $this->filterWorkAvailability($query, $request);
            $this->filterExperience($query, $request);
            $this->filterMainSpecializations($query, $request);
            $this->filterSubSpecialities($query, $request);

            Log::info('Final SQL query: ' . $query->toSql());
            Log::info('Query bindings: ' . json_encode($query->getBindings()));
        } catch (\Exception $e) {
            Log::error('Error in ApplicantFilterService: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function filterGender(Builder $query, Request $request): void
    {
        if ($request->filled('gender')) {
            $genderValue = strtolower($request->input('gender'));
            Log::info('Filtering by gender: ' . $genderValue);
            if ($this->isPostgres) {
                $query->whereRaw("LOWER(contact->>'gender') = ?", [$genderValue]);
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.gender'))) = ?", [$genderValue]);
            }
        }
    }

    protected function filterCity(Builder $query, Request $request): void
    {
        if ($request->filled('city')) {
            $cityValue = strtolower($request->input('city'));
            Log::info('Filtering by city: ' . $cityValue);
            if ($this->isPostgres) {
                $query->whereRaw("LOWER(contact->>'city') = ?", [$cityValue]);
            } else {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.city'))) = ?", [$cityValue]);
            }

            if (strtolower($request->input('city')) === 'baghdad' && $request->filled('zone')) {
                $zoneValue = strtolower($request->input('zone'));
                Log::info('Filtering by zone: ' . $zoneValue);
                if ($this->isPostgres) {
                    $query->whereRaw("LOWER(contact->>'zone') = ?", [$zoneValue]);
                } else {
                    $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(contact, '$.zone'))) = ?", [$zoneValue]);
                }
            }
        }
    }

    protected function filterAgeRange(Builder $query, Request $request): void
    {
        if ($request->filled('age') && is_array($request->input('age')) && count($request->input('age')) == 2) {
            $ageRange = array_map('intval', $request->input('age'));
            if (min($ageRange) < 0 || max($ageRange) > 150) {
                Log::warning('Invalid age range provided: ' . json_encode($ageRange));
                return;
            }
            Log::info('Filtering by age range: ' . json_encode($ageRange));
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
            Log::info('Filtering by degree: ' . $degreeValue);
            if ($this->isPostgres) {
                $query->whereRaw("EXISTS (SELECT 1 FROM jsonb_array_elements(education::jsonb) AS edu WHERE LOWER(edu->>'degree') = ?)", [$degreeValue]);
            } else {
                $query->whereRaw("JSON_SEARCH(LOWER(education), 'one', ?) IS NOT NULL", [$degreeValue]);
            }
        }
    }

    protected function filterFreshGraduate(Builder $query, Request $request): void
    {
        if ($request->filled('freshGraduate')) {
            $isFreshGraduate = filter_var($request->input('freshGraduate'), FILTER_VALIDATE_BOOLEAN);
            Log::info('Filtering by fresh graduate: ' . ($isFreshGraduate ? 'true' : 'false'));
            if ($isFreshGraduate) {
                $twoYearsAgo = now()->subYears(2)->startOfYear()->year;
                $currentYear = now()->year;
                if ($this->isPostgres) {
                    $query->whereRaw(
                        "EXISTS (
                            SELECT 1
                            FROM jsonb_array_elements(education::jsonb) AS edu
                            WHERE LOWER(edu->>'degree') = ?
                            AND (
                                CASE
                                    WHEN jsonb_typeof(edu->'duration'->1) = 'number'
                                    THEN (edu->'duration'->1)::int
                                    WHEN jsonb_typeof(edu->'duration'->1) = 'string' AND (edu->'duration'->1)::text ~ '^[0-9]+$'
                                    THEN (edu->'duration'->1)::text::int
                                    ELSE EXTRACT(YEAR FROM CURRENT_DATE)
                                END
                            ) BETWEEN ? AND ?
                        )",
                        [strtolower("Bachelor's Degree"), $twoYearsAgo, $currentYear]
                    );
                } else {
                    $query->whereRaw(
                        "EXISTS (
                            SELECT 1
                            FROM JSON_TABLE(
                                education,
                                '$[*]' COLUMNS (
                                    degree VARCHAR(255) PATH '$.degree',
                                    grad_year VARCHAR(255) PATH '$.duration[1]'
                                )
                            ) AS edu
                            WHERE LOWER(edu.degree) = ?
                            AND CASE
                                WHEN edu.grad_year = 'present' THEN ?
                                WHEN edu.grad_year REGEXP '^[0-9]+$' THEN CAST(edu.grad_year AS UNSIGNED)
                                ELSE ?
                            END BETWEEN ? AND ?
                        )",
                        [strtolower("Bachelor's Degree"), $currentYear, $currentYear, $twoYearsAgo, $currentYear]
                    );
                }
            }
        }
    }

    protected function filterWorkAvailability(Builder $query, Request $request): void
    {
        if ($request->filled('workAvailability')) {
            $isAvailable = filter_var($request->input('workAvailability'), FILTER_VALIDATE_BOOLEAN);
            Log::info('Filtering by work availability: ' . ($isAvailable ? 'true' : 'false'));
            if ($this->isPostgres) {
                $query->whereRaw("(contact->>'workAvailability')::boolean = ?", [$isAvailable]);
            } else {
                $query->whereRaw(
                    "CASE
                    WHEN JSON_EXTRACT(contact, '$.workAvailability') = 'true' THEN 1
                    WHEN JSON_EXTRACT(contact, '$.workAvailability') = 'false' THEN 0
                    ELSE JSON_EXTRACT(contact, '$.workAvailability')
                END = ?",
                    [$isAvailable ? 1 : 0]
                );
            }
        }
    }
    protected function filterExperience(Builder $query, Request $request): void
    {
        if ($request->filled('experience') && is_array($request->input('experience')) && count($request->input('experience')) == 2) {
            $experienceRange = array_map('intval', $request->input('experience'));
            Log::info('Filtering by experience range: ' . json_encode($experienceRange));
            if ($this->isPostgres) {
                // PostgreSQL query remains unchanged
                $query->whereRaw(
                    "COALESCE((
                    SELECT SUM(
                        CASE
                            WHEN (job->>'duration')::json->>1 = 'present' THEN
                                EXTRACT(YEAR FROM CURRENT_DATE) - NULLIF((job->>'duration')::json->>0, '')::int
                            WHEN (job->>'duration')::json->>1 ~ '^[0-9]+$' AND (job->>'duration')::json->>0 ~ '^[0-9]+$' THEN
                                NULLIF((job->>'duration')::json->>1, '')::int - NULLIF((job->>'duration')::json->>0, '')::int
                            ELSE 0
                        END
                    ) FROM jsonb_array_elements(employment::jsonb) AS job
                ), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            } else {
                // Updated MySQL query
                $query->whereRaw(
                    "COALESCE((
                    SELECT SUM(
                        CASE
                            WHEN JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[1]'))) = 'present' THEN
                                YEAR(CURDATE()) - CAST(JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[0]'))) AS UNSIGNED)
                            WHEN JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[1]'))) REGEXP '^[0-9]+$'
                                 AND JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[0]'))) REGEXP '^[0-9]+$' THEN
                                CAST(JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[1]'))) AS UNSIGNED)
                                - CAST(JSON_UNQUOTE(JSON_EXTRACT(employment, CONCAT('$[', job_index, '].duration[0]'))) AS UNSIGNED)
                            ELSE 0
                        END
                    )
                    FROM (
                        SELECT a.id, b.job_index
                        FROM applicants a
                        JOIN (
                            SELECT 0 AS job_index
                            UNION ALL SELECT 1
                            UNION ALL SELECT 2
                            UNION ALL SELECT 3
                            UNION ALL SELECT 4
                            -- Add more UNION ALL SELECT statements if you need to support more than 5 jobs
                        ) b
                        WHERE b.job_index < JSON_LENGTH(a.employment)
                    ) as job_indices
                    WHERE job_indices.id = applicants.id
                ), 0) BETWEEN ? AND ?",
                    [min($experienceRange), max($experienceRange)]
                );
            }
        }
    }
    protected function filterMainSpecializations(Builder $query, Request $request): void
    {
        if ($request->filled('mainSpecializations')) {
            $mainSpecialization = strtolower($request->input('mainSpecializations')[0]);
            Log::info('Filtering by main specialization: ' . $mainSpecialization);

            if ($this->isPostgres) {
                $query->whereRaw("
                    EXISTS (
                        SELECT 1
                        FROM jsonb_array_elements_text(speciality::jsonb->'specializations') AS specialization
                        WHERE lower(specialization) = ?
                    )
                ", [$mainSpecialization]);
            } else {
                $query->whereRaw("
                    JSON_SEARCH(LOWER(JSON_EXTRACT(speciality, '$.specializations')), 'one', ?) IS NOT NULL
                ", [$mainSpecialization]);
            }

            // Log the SQL query and bindings
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Query Bindings: ' . json_encode($query->getBindings()));
        }
    }

    protected function filterSubSpecialities(Builder $query, Request $request): void
    {
        if ($request->filled('subSpecialities') && is_array($request->input('subSpecialities'))) {
            $subSpecialities = array_map('strtolower', $request->input('subSpecialities'));
            Log::info('Filtering by sub specialities: ' . json_encode($subSpecialities));

            if ($this->isPostgres) {
                $placeholders = implode(',', array_fill(0, count($subSpecialities), '?'));
                $query->whereRaw("
                    EXISTS (
                        SELECT 1
                        FROM jsonb_array_elements_text(speciality::jsonb->'children') AS child
                        WHERE lower(child) IN ($placeholders)
                    )
                ", $subSpecialities);
            } else {
                $conditions = [];
                foreach ($subSpecialities as $index => $subSpeciality) {
                    $conditions[] = "JSON_SEARCH(LOWER(JSON_EXTRACT(speciality, '$.children')), 'one', ?) IS NOT NULL";
                }

                $query->whereRaw('(' . implode(' OR ', $conditions) . ')', $subSpecialities);
            }

            // Log the SQL query and bindings
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Query Bindings: ' . json_encode($query->getBindings()));
        }
    }
}
