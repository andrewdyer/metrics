<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents an order Eloquent model for use in tests.
 */
class Order extends Model
{
    /**
     * The database table associated with the model.
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total',
        'status',
        'country',
        'created_at',
        'updated_at',
    ];
}
