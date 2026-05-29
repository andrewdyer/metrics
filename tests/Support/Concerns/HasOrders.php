<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Concerns;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

/**
 * Handles database setup and teardown for order-related test cases.
 */
trait HasOrders
{
    /**
     * Builds the orders table in the test database.
     */
    protected function migrateOrdersTable(): void
    {
        Capsule::schema()->create('orders', function(Blueprint $table): void {
            $table->id();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('country')->default('GB');
            $table->timestamps();
        });
    }

    /**
     * Deletes the orders table from the test database.
     */
    protected function dropOrdersTable(): void
    {
        Capsule::schema()->dropIfExists('orders');
    }
}
