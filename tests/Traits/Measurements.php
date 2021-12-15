<?php

namespace Anddye\Metrics\Tests\Traits;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

trait Measurements
{
    protected function dropMeasurementsTable(): void
    {
        Capsule::schema()->drop('measurements');
    }

    protected function migrateMeasurementsTable(): void
    {
        Capsule::schema()->create('measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
            $table->unsignedInteger('user_id');
            $table->string('weight_kg');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
}
