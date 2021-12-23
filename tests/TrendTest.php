<?php

namespace Anddye\Metrics\Tests;

use Anddye\Metrics\Tests\Fixtures\Metrics\AverageTrend;
use Anddye\Metrics\Tests\Fixtures\Metrics\CountTrend;
use Anddye\Metrics\Tests\Fixtures\Metrics\MaxTrend;
use Anddye\Metrics\Tests\Fixtures\Metrics\MinTrend;
use Anddye\Metrics\Tests\Fixtures\Metrics\SumTrend;
use Anddye\Metrics\Tests\Fixtures\Models\Measurement;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Anddye\Metrics\Tests\Traits\Measurements;
use Anddye\Metrics\Tests\Traits\Users;

final class TrendTest extends MetricTest
{
    use Measurements;
    use Users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateUsersTable();
        $this->migrateMeasurementsTable();
    }

    protected function tearDown(): void
    {
        $this->dropMeasurementsTable();
        $this->dropUsersTable();
    }

    public function testCanCalculateAverageTrend(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2020-05-10 09:11:09', 'weight_kg' => 68, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-15 10:22:56', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-20 07:38:26', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-25 07:20:49', 'weight_kg' => 64, 'user_id' => 1]);

        $result = (new AverageTrend())->calculate();

        $this->assertIsArray($result->getTrend());
        $this->assertCount(3, $result->getTrend());
        $this->assertArrayHasKey('May 2020', $result->getTrend());
        $this->assertEquals(66, $result->getTrend()['May 2020']);
    }

    public function testCanCalculateCountTrend(): void
    {
        User::create(['first_name' => 'Oliver', 'last_name' => 'Hill', 'signed_up_at' => '2020-03-27 14:51:42']);
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        User::create(['first_name' => 'Joseph', 'last_name' => 'Ward', 'signed_up_at' => '2020-05-14 11:59:40']);
        User::create(['first_name' => 'Hannah', 'last_name' => 'King', 'signed_up_at' => '2020-05-30 12:31:27']);
        User::create(['first_name' => 'Daniel', 'last_name' => 'Cook', 'signed_up_at' => '2020-06-06 10:15:54']);
        User::create(['first_name' => 'Violet', 'last_name' => 'Reed', 'signed_up_at' => '2020-06-11 16:13:33']);
        User::create(['first_name' => 'Amelia', 'last_name' => 'Bell', 'signed_up_at' => '2020-07-22 20:21:40']);

        $result = (new CountTrend())->calculate();

        $this->assertIsArray($result->getTrend());
        $this->assertCount(5, $result->getTrend());
        $this->assertArrayHasKey('March 2020', $result->getTrend());
        $this->assertArrayHasKey('April 2020', $result->getTrend());
        $this->assertArrayHasKey('May 2020', $result->getTrend());
        $this->assertArrayHasKey('June 2020', $result->getTrend());
        $this->assertArrayHasKey('July 2020', $result->getTrend());
        $this->assertEquals(1, $result->getTrend()['March 2020']);
        $this->assertEquals(0, $result->getTrend()['April 2020']);
        $this->assertEquals(3, $result->getTrend()['May 2020']);
        $this->assertEquals(2, $result->getTrend()['June 2020']);
        $this->assertEquals(1, $result->getTrend()['July 2020']);
    }

    public function testCanCalculateMaxTrend(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2020-04-29 07:32:22', 'weight_kg' => 69, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-10 09:11:09', 'weight_kg' => 68, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-15 10:22:56', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-20 07:38:26', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-25 07:20:49', 'weight_kg' => 64, 'user_id' => 1]);
        Measurement::create(['date' => '2020-06-01 08:01:09', 'weight_kg' => 63, 'user_id' => 1]);

        $result = (new MaxTrend())->calculate();

        $this->assertIsArray($result->getTrend());
        $this->assertCount(3, $result->getTrend());
        $this->assertArrayHasKey('April 2020', $result->getTrend());
        $this->assertArrayHasKey('May 2020', $result->getTrend());
        $this->assertArrayHasKey('June 2020', $result->getTrend());
        $this->assertEquals(69, $result->getTrend()['April 2020']);
        $this->assertEquals(68, $result->getTrend()['May 2020']);
        $this->assertEquals(63, $result->getTrend()['June 2020']);
    }

    public function testCanCalculateMinTrend(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2020-04-29 07:32:22', 'weight_kg' => 69, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-10 09:11:09', 'weight_kg' => 68, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-15 10:22:56', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-20 07:38:26', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-25 07:20:49', 'weight_kg' => 64, 'user_id' => 1]);
        Measurement::create(['date' => '2020-06-01 08:01:09', 'weight_kg' => 63, 'user_id' => 1]);

        $result = (new MinTrend())->calculate();

        $this->assertIsArray($result->getTrend());
        $this->assertCount(3, $result->getTrend());
        $this->assertArrayHasKey('April 2020', $result->getTrend());
        $this->assertArrayHasKey('May 2020', $result->getTrend());
        $this->assertArrayHasKey('June 2020', $result->getTrend());
        $this->assertEquals(69, $result->getTrend()['April 2020']);
        $this->assertEquals(64, $result->getTrend()['May 2020']);
        $this->assertEquals(63, $result->getTrend()['June 2020']);
    }

    public function testCanCalculateSumTrend(): void
    {
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24']);
        Measurement::create(['date' => '2020-04-29 07:32:22', 'weight_kg' => 69, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-10 09:11:09', 'weight_kg' => 68, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-15 10:22:56', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-20 07:38:26', 'weight_kg' => 66, 'user_id' => 1]);
        Measurement::create(['date' => '2020-05-25 07:20:49', 'weight_kg' => 64, 'user_id' => 1]);
        Measurement::create(['date' => '2020-06-01 08:01:09', 'weight_kg' => 63, 'user_id' => 1]);

        $result = (new SumTrend())->calculate();
        $this->assertIsArray($result->getTrend());
        $this->assertCount(3, $result->getTrend());
        $this->assertArrayHasKey('April 2020', $result->getTrend());
        $this->assertArrayHasKey('May 2020', $result->getTrend());
        $this->assertArrayHasKey('June 2020', $result->getTrend());
        $this->assertEquals(69, $result->getTrend()['April 2020']);
        $this->assertEquals(264, $result->getTrend()['May 2020']);
        $this->assertEquals(63, $result->getTrend()['June 2020']);
    }
}
