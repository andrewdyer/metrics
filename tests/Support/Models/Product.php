<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a product Eloquent model without timestamps for use in tests.
 */
class Product extends Model
{
    /**
     * The database table associated with the model.
     */
    protected $table = 'products';

    /**
     * Indicates whether the model has timestamp columns.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'price',
    ];
}
