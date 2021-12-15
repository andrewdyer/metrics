<?php

namespace Anddye\Metrics\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    protected $fillable = ['calories', 'carbohydrates', 'date', 'fat', 'fiber', 'name', 'protein', 'user_id'];
    protected $table = 'nutrition';
}
