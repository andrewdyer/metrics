<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit;

use AndrewDyer\Metrics\Results\ValueResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;
use AndrewDyer\Metrics\Tests\Support\Concerns\HasOrders;
use AndrewDyer\Metrics\Tests\Support\Metrics\Value\TestValueMetric;
use AndrewDyer\Metrics\Tests\Support\Models\Order;
use DateTimeImmutable;

/**
 * Unit tests for Value.
 */
final class ValueTest extends AbstractTestCase
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
        Order::create(['total' => 300.00, 'status' => 'complete', 'country' => 'DE', 'created_at' => '2025-12-31']);
    }

    /**
     * Deletes the orders table after each test.
     */
    protected function tearDown(): void
    {
        $this->dropOrdersTable();
    }

    /**
     * Asserts that count returns the correct number of records.
     */
    public function testCountReturnsCorrectValue(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->count(Order::query());

        $this->assertInstanceOf(ValueResult::class, $result);
        $this->assertSame(3, $result->getResult());
    }

    /**
     * Asserts that sum returns the correct total.
     */
    public function testSumReturnsCorrectValue(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->sum(Order::query(), 'total');

        $this->assertInstanceOf(ValueResult::class, $result);
        $this->assertSame(350, $result->getResult());
    }

    /**
     * Asserts that average returns the correct rounded value.
     */
    public function testAverageReturnsCorrectValue(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->average(Order::query(), 'total');

        $this->assertInstanceOf(ValueResult::class, $result);
        $this->assertSame(117, $result->getResult());
    }

    /**
     * Asserts that min returns the correct minimum value.
     */
    public function testMinReturnsCorrectValue(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->min(Order::query(), 'total');

        $this->assertInstanceOf(ValueResult::class, $result);
        $this->assertSame(50, $result->getResult());
    }

    /**
     * Asserts that max returns the correct maximum value.
     */
    public function testMaxReturnsCorrectValue(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->max(Order::query(), 'total');

        $this->assertInstanceOf(ValueResult::class, $result);
        $this->assertSame(200, $result->getResult());
    }

    /**
     * Asserts that count excludes records outside the configured date range.
     */
    public function testCountExcludesRecordsOutsideDateRange(): void
    {
        $metric = new TestValueMetric($this->start, $this->end);
        $result = $metric->count(Order::query());

        $this->assertSame(3, $result->getResult());
    }

    /**
     * Asserts that the configured rounding precision is applied to the result.
     */
    public function testRoundingPrecisionIsApplied(): void
    {
        $metric = new class ($this->start, $this->end) extends TestValueMetric {
            public function getRoundingPrecision(): int
            {
                return 2;
            }
        };

        $result = $metric->average(Order::query(), 'total');

        $this->assertSame(116.67, $result->getResult());
    }
}
