<?php

namespace Anddye\Metrics\Tests\Traits;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

trait Users
{
    protected function dropUsersTable(): void
    {
        Capsule::schema()->drop('users');
    }

    protected function migrateUsersTable(): void
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('signed_up_at');
            $table->enum('stripe_plan', ['none', 'daily', 'monthly', 'yearly'])->default('none');
            $table->timestamps();
        });
    }
}
