<?php

namespace Anddye\Metrics\Tests;

use Anddye\Metrics\Tests\Fixtures\Metrics\UsersPerPlanPartition;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Anddye\Metrics\Tests\Traits\Users;

final class PartitionTest extends MetricTest
{
    use Users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateUsersTable();
    }

    protected function tearDown(): void
    {
        $this->dropUsersTable();
    }

    public function testCanCalculateCountPartition(): void
    {
        User::create(['first_name' => 'Oliver', 'last_name' => 'Hill', 'signed_up_at' => '2020-03-27 14:51:42', 'stripe_plan' => 'yearly']);
        User::create(['first_name' => 'Andrew', 'last_name' => 'Dyer', 'signed_up_at' => '2020-05-04 11:23:24', 'stripe_plan' => 'none']);
        User::create(['first_name' => 'Joseph', 'last_name' => 'Ward', 'signed_up_at' => '2020-05-14 11:59:40', 'stripe_plan' => 'daily']);
        User::create(['first_name' => 'Hannah', 'last_name' => 'King', 'signed_up_at' => '2020-05-30 12:31:27', 'stripe_plan' => 'daily']);
        User::create(['first_name' => 'Daniel', 'last_name' => 'Cook', 'signed_up_at' => '2020-06-06 10:15:54', 'stripe_plan' => 'monthly']);
        User::create(['first_name' => 'Violet', 'last_name' => 'Reed', 'signed_up_at' => '2020-06-11 16:13:33', 'stripe_plan' => 'monthly']);
        User::create(['first_name' => 'Amelia', 'last_name' => 'Bell', 'signed_up_at' => '2020-07-22 20:21:40', 'stripe_plan' => 'monthly']);

        $metric = new UsersPerPlanPartition();

        $result = $metric->calculate()->getResult();

        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertArrayHasKey('none', $result);
        $this->assertArrayHasKey('daily', $result);
        $this->assertArrayHasKey('monthly', $result);
        $this->assertArrayHasKey('yearly', $result);
        $this->assertEquals(1, $result['none']);
        $this->assertEquals(2, $result['daily']);
        $this->assertEquals(3, $result['monthly']);
        $this->assertEquals(1, $result['yearly']);
    }
}
