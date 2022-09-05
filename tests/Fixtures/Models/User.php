<?php

namespace Anddye\Metrics\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = ['first_name', 'last_name', 'signed_up_at', 'stripe_plan'];

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class)->latest('date');
    }

    public function nutrition(): HasMany
    {
        return $this->hasMany(Nutrition::class)->latest('date');
    }
}
