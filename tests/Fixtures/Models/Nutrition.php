<?php

namespace Anddye\Metrics\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nutrition extends Model
{
    protected $fillable = ['calories', 'carbohydrates', 'date', 'fat', 'fiber', 'name', 'protein', 'user_id'];
    protected $table = 'nutrition';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
