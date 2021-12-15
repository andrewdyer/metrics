<?php

namespace Anddye\Metrics\Tests\Traits;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

trait Nutritions
{
    protected function dropNutritionTable(): void
    {
        Capsule::schema()->drop('nutrition');
    }

    protected function migrateNutritionTable(): void
    {
        Capsule::schema()->create('nutrition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calories');
            $table->integer('carbohydrates');
            $table->date('date');
            $table->integer('fat');
            $table->string('name');
            $table->integer('fiber');
            $table->integer('protein');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
