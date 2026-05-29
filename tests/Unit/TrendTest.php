<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Results\TrendResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;
use AndrewDyer\Metrics\Tests\Support\Concerns\HasOrders;
use AndrewDyer\Metrics\Tests\Support\Metrics\Trend\TestTrendMetric;
use AndrewDyer\Metrics\Tests\Support\Models\Order;
use DateTimeImmutable;

/**
 * Unit tests for Trend.
 */
final class TrendTest extends AbstractTestCase
{
    use HasOrders;

    /**
     * Builds the test database and seeds order data.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateOrdersTable();

        Order::create(['total' => 100.00, 'status' => 'complete', 'country' => 'GB', 'created_at' => '2026-01-15']);
        Order::create(['total' => 200.00, 'status' => 'complete', 'country' => 'US', 'created_at' => '2026-01-20']);
        Order::create(['total' => 50.00, 'status' => 'pending', 'country' => 'GB', 'created_at' => '2026-02-10']);
        Order::create(['total' => 300.00, 'status' => 'complete', 'country' => 'DE', 'created_at' => '2026-03-05']);
    }

    /**
     * Deletes the orders table after each test.
     */
    protected function tearDown(): void
    {
        $this->dropOrdersTable();
    }

    /**
     * Asserts that counting monthly returns the correct trend data.
     */
    public function testCountMonthlyReturnsCorrectTrend(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $result = $metric->count(Order::query(), null, 'created_at');

        $this->assertInstanceOf(TrendResult::class, $result);

        $data = $result->getResult();

        $this->assertArrayHasKey('January 2026', $data);
        $this->assertArrayHasKey('February 2026', $data);
        $this->assertArrayHasKey('March 2026', $data);
        $this->assertEquals(2, $data['January 2026']);
        $this->assertEquals(1, $data['February 2026']);
        $this->assertEquals(1, $data['March 2026']);
    }

    /**
     * Asserts that summing monthly returns the correct trend totals.
     */
    public function testSumMonthlyReturnsCorrectTrend(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $result = $metric->sum(Order::query(), 'total', 'created_at');

        $this->assertInstanceOf(TrendResult::class, $result);

        $data = $result->getResult();

        $this->assertEquals(300, $data['January 2026']);
        $this->assertEquals(50, $data['February 2026']);
        $this->assertEquals(300, $data['March 2026']);
    }

    /**
     * Asserts that missing dates in the range are filled with zero.
     */
    public function testMissingDatesAreFilledWithZero(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-30'),
            Frequency::Monthly,
        );

        $result = $metric->count(Order::query(), null, 'created_at');
        $data = $result->getResult();

        $this->assertArrayHasKey('April 2026', $data);
        $this->assertArrayHasKey('May 2026', $data);
        $this->assertArrayHasKey('June 2026', $data);
        $this->assertSame(0, $data['April 2026']);
        $this->assertSame(0, $data['May 2026']);
        $this->assertSame(0, $data['June 2026']);
    }

    /**
     * Asserts that counting daily returns the correct trend data.
     */
    public function testCountDailyReturnsCorrectTrend(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-15'),
            new DateTimeImmutable('2026-01-20'),
            Frequency::Daily,
        );

        $result = $metric->count(Order::query(), null, 'created_at');
        $data = $result->getResult();

        $this->assertArrayHasKey('January 15, 2026', $data);
        $this->assertArrayHasKey('January 20, 2026', $data);
        $this->assertEquals(1, $data['January 15, 2026']);
        $this->assertEquals(1, $data['January 20, 2026']);
    }

    /**
     * Asserts that counting weekly returns a non-empty trend result.
     */
    public function testCountWeeklyReturnsCorrectTrend(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-31'),
            Frequency::Weekly,
        );

        $result = $metric->count(Order::query(), null, 'created_at');

        $this->assertInstanceOf(TrendResult::class, $result);
        $this->assertNotEmpty($result->getResult());
    }

    /**
     * Asserts that all expected date keys are present for the given date range.
     */
    public function testAllKeysArePresentForDateRange(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $result = $metric->count(Order::query(), null, 'created_at');
        $data = $result->getResult();

        $this->assertCount(3, $data);
    }

    /**
     * Asserts that getName returns the short class name of the metric.
     */
    public function testGetNameReturnsShortClassName(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-12-31'),
        );

        $this->assertSame('TestTrendMetric', $metric->getName());
    }

    /**
     * Asserts that jsonSerialize formats the start and end dates as strings.
     */
    public function testJsonSerializeDateFormattedAsString(): void
    {
        $metric = new TestTrendMetric(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-12-31'),
            Frequency::Monthly,
        );

        $json = $metric->jsonSerialize();

        $this->assertSame('2026-01-01', $json['dates']['start']);
        $this->assertSame('2026-12-31', $json['dates']['end']);
        $this->assertSame(Frequency::Monthly, $json['frequency']);
    }
}
