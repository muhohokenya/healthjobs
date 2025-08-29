<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'selected_role',
        'licence_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isProfileComplete(): bool
    {
        // basic fields on users table
        if (empty($this->name) || empty($this->email)) {
            return false;
        }

        // if jobseeker, check profile details
        if ($this->hasRole('job-seeker')) {
            return !empty($this->licence_number)
                && !empty($this->licence_number_expiry)
                && !empty($this->licence_status)
                && $this->licence_status === 'active'
                && $this->licence_number_expiry > now(); // Check if license is not expired
        }

        if ($this->hasRole('recruiter')) {
            return !empty($this->licence_number)
                && !empty($this->licence_number_expiry)
                && !empty($this->licence_status)
                && $this->licence_status === 'active'
                && $this->licence_number_expiry > now(); // Check if license is not expired
        }

        // employers only need name + email for now
        return true;
    }

//    public function facility():HasOne
//    {
//        return $this->hasOne(Facility::class);
//    }
}
