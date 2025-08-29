<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Facility extends Model
{
    protected $guarded = [];



    public function user():BelongsTo
    {
        return $this->hasOne(User::class);
    }
}
