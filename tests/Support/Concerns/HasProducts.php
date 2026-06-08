<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Concerns;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

/**
 * Handles database setup and teardown for product-related test cases.
 */
trait HasProducts
{
    /**
     * Builds the products table in the test database.
     */
    protected function migrateProductsTable(): void
    {
        Capsule::schema()->create('products', function(Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2)->default(0);
        });
    }

    /**
     * Deletes the products table from the test database.
     */
    protected function dropProductsTable(): void
    {
        Capsule::schema()->dropIfExists('products');
    }
}
