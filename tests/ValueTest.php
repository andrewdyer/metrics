<?php

namespace Anddye\Metrics\Tests;

use Anddye\Metrics\Tests\Fixtures\Metrics\AverageValue;
use Anddye\Metrics\Tests\Fixtures\Metrics\MaxValue;
use Anddye\Metrics\Tests\Fixtures\Metrics\MinValue;
use Anddye\Metrics\Tests\Fixtures\Metrics\SumValue;
use Anddye\Metrics\Tests\Fixtures\Metrics\UserGrowth;
use Anddye\Metrics\Tests\Fixtures\Models\Measurement;
use Anddye\Metrics\Tests\Fixtures\Models\Nutrition;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Anddye\Metrics\Tests\Traits\Measurements;
use Anddye\Metrics\Tests\Traits\Nutritions;
use Anddye\Metrics\Tests\Traits\Users;

final class ValueTest extends AbstractTestCase
{
    use Measurements;
    use Nutritions;
    use Users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateUsersTable();
        $this->migrateMeasurementsTable();
        $this->migrateNutritionTable();
    }

    protected function tearDown(): void
    {
        $this->dropNutritionTable();
        $this->dropMeasurementsTable();
        $this->dropUsersTable();
    }

    public function testCanCalculateAverage(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        User::create(['first_name' => 'Joseph', 'last_name' => 'Ward', 'signed_up_at' => '2020-05-14 11:59:40']);
        Measurement::create(['date' => '2020-05-10 09:11:09', 'weight_kg' => 68, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-15 10:22:56', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-20 07:38:26', 'weight_kg' => 66, 'user_id' => 2]);
        Measurement::create(['date' => '2020-05-25 07:20:49', 'weight_kg' => 64, 'user_id' => 2]);

        $metric = new AverageValue();

        $result = $metric->calculate()->getResult();

        $this->assertEquals(67, $result);
    }

    public function testCanCalculateCount(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        User::create(['first_name' => 'Joseph', 'last_name' => 'Ward', 'signed_up_at' => '2020-05-14 11:59:40']);
        User::create(['first_name' => 'Hannah', 'last_name' => 'King', 'signed_up_at' => '2020-05-30 12:31:27']);

        $metric = new UserGrowth('2020-05-01 00:00:00', '2020-05-31 23:59:00');

        $result = $metric->calculate()->getResult();

        $this->assertEquals(3, $result);
    }

    public function testCanCalculateMax(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2021-01-01 11:19:30', 'weight_kg' => 72, 'user_id' => 1]);
        Measurement::create(['date' => '2021-02-01 10:33:18', 'weight_kg' => 70, 'user_id' => 1]);
        Measurement::create(['date' => '2021-03-01 09:48:12', 'weight_kg' => 67, 'user_id' => 1]);
        Measurement::create(['date' => '2021-04-01 09:26:04', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2021-05-01 11:56:42', 'weight_kg' => 65, 'user_id' => 1]);

        $metric = new MaxValue();

        $result = $metric->calculate()->getResult();

        $this->assertEquals(72, $result);
    }

    public function testCanCalculateMin(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2021-01-01 11:19:30', 'weight_kg' => 72, 'user_id' => 1]);
        Measurement::create(['date' => '2021-02-01 10:33:18', 'weight_kg' => 70, 'user_id' => 1]);
        Measurement::create(['date' => '2021-03-01 09:48:12', 'weight_kg' => 67, 'user_id' => 1]);
        Measurement::create(['date' => '2021-04-01 09:26:04', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2021-05-01 11:56:42', 'weight_kg' => 65, 'user_id' => 1]);

        $metric = new MinValue();

        $result = $metric->calculate()->getResult();

        $this->assertEquals(65, $result);
    }

    public function testCanCalculateSum(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Nutrition::create(['name' => 'Chicken Breast', 'calories' => 192, 'carbohydrates' => 4, 'fat' => 2, 'fiber' => 0, 'protein' => 39, 'user_id' => 1, 'date' => '2020-11-26 12:31:11']);
        Nutrition::create(['name' => 'Rice', 'calories' => 231, 'carbohydrates' => 44, 'fat' => 4, 'fiber' => 0, 'protein' => 5, 'user_id' => 1, 'date' => '2020-11-26 12:31:11']);
        Nutrition::create(['name' => 'Broccoli', 'calories' => 3, 'carbohydrates' => 0, 'fat' => 0, 'fiber' => 0, 'protein' => 0, 'user_id' => 1, 'date' => '2020-11-26 12:31:11']);

        $metric = new SumValue();

        $result = $metric->calculate()->getResult();

        $this->assertEquals(426, $result);
    }
}
