<?php

namespace Anddye\Metrics\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    protected $fillable = ['time_of_measurement', 'user_id', 'weight_kg'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
