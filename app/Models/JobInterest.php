<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobInterest extends Model
{

    protected $fillable = [
        'user_id',
        'health_job_id',
    ];

    /**
     * Get the user who showed interest
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job that was interested in
     */
    public function healthJob(): BelongsTo
    {
        return $this->belongsTo(HealthJob::class);
    }


    /**
     * Get all interests for this job
     */
    public function interests()
    {
        return $this->hasMany(JobInterest::class);
    }

    /**
     * Get all users interested in this job
     */
    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'job_interests');
    }

    /**
     * Check if a specific user is interested in this job
     */
    public function isInterestedBy(User $user): bool
    {
        return $this->interests()->where('user_id', $user->id)->exists();
    }


}
