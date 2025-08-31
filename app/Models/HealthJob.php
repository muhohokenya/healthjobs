<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthJob extends Model
{
    use HasFactory;

    // In your HealthJob model
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    // App\Models\HealthJob.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
        // 'user_id' should be the FK column in your health_jobs table
    }

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'job_type',
        'salary_min',
        'salary_max',
        'experience_level',
        'requirements',
        'is_active',
        'user_id',
        'facility_id',
    ];

    protected $casts = [
        'requirements' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_active' => 'boolean',
        'qualifications' => 'array',
    ];
}
