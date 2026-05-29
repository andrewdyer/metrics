<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit;

use AndrewDyer\Metrics\Results\PartitionResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;
use AndrewDyer\Metrics\Tests\Support\Concerns\HasOrders;
use AndrewDyer\Metrics\Tests\Support\Metrics\Partition\TestPartitionMetric;
use AndrewDyer\Metrics\Tests\Support\Models\Order;
use DateTimeImmutable;

/**
 * Unit tests for Partition.
 */
final class PartitionTest extends AbstractTestCase
{
    use HasOrders;

    /**
     * The metric start date.
     */
    private DateTimeImmutable $start;

    /**
     * The metric end date.
     */
    private DateTimeImmutable $end;

    /**
     * Builds the test database, seeds order data, and initialises the date range.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateOrdersTable();

        $this->start = new DateTimeImmutable('2026-01-01');
        $this->end = new DateTimeImmutable('2026-12-31');

        Order::create(['total' => 100.00, 'status' => 'complete', 'country' => 'GB', 'created_at' => '2026-03-01']);
        Order::create(['total' => 200.00, 'status' => 'complete', 'country' => 'US', 'created_at' => '2026-03-15']);
        Order::create(['total' => 50.00, 'status' => 'pending', 'country' => 'GB', 'created_at' => '2026-06-10']);
        Order::create(['total' => 300.00, 'status' => 'complete', 'country' => 'DE', 'created_at' => '2026-09-01']);
    }

    /**
     * Deletes the orders table after each test.
     */
    protected function tearDown(): void
    {
        $this->dropOrdersTable();
    }

    /**
     * Asserts that counting by country returns the correct partition result.
     */
    public function testCountByCountryReturnsCorrectPartition(): void
    {
        $metric = new TestPartitionMetric($this->start, $this->end);
        $result = $metric->count(Order::query(), 'country');

        $this->assertInstanceOf(PartitionResult::class, $result);
        $this->assertEqualsCanonicalizing(['GB' => 2, 'US' => 1, 'DE' => 1], $result->getResult());
    }

    /**
     * Asserts that counting by status returns the correct partition result.
     */
    public function testCountByStatusReturnsCorrectPartition(): void
    {
        $metric = new TestPartitionMetric($this->start, $this->end);
        $result = $metric->count(Order::query(), 'status');

        $this->assertInstanceOf(PartitionResult::class, $result);
        $this->assertSame(['complete' => 3, 'pending' => 1], $result->getResult());
    }

    /**
     * Asserts that summing by country returns the correct partition result.
     */
    public function testSumByCountryReturnsCorrectPartition(): void
    {
        $metric = new TestPartitionMetric($this->start, $this->end);
        $result = $metric->sum(Order::query(), 'country', 'total');

        $this->assertInstanceOf(PartitionResult::class, $result);
        $data = $result->getResult();
        $this->assertEqualsCanonicalizing(['GB', 'US', 'DE'], array_keys($data));
        $this->assertEquals(150, $data['GB']);
        $this->assertEquals(200, $data['US']);
        $this->assertEquals(300, $data['DE']);
    }

    /**
     * Asserts that averaging by country returns a partition result with the expected group keys.
     */
    public function testAverageByCountryReturnsCorrectPartition(): void
    {
        $metric = new TestPartitionMetric($this->start, $this->end);
        $result = $metric->average(Order::query(), 'country', 'total');

        $this->assertInstanceOf(PartitionResult::class, $result);

        $data = $result->getResult();
        $this->assertArrayHasKey('GB', $data);
        $this->assertArrayHasKey('US', $data);
        $this->assertArrayHasKey('DE', $data);
    }
}
