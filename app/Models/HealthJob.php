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

    protected $fillable = [
        'title',
        'company',
        'description',
        'location',
        'job_type',
        'salary_min',
        'salary_max',
        'experience_level',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'requirements' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
