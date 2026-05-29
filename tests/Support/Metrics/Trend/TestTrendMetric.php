<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Metrics\Trend;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Results\Result;
use AndrewDyer\Metrics\Results\TrendResult;
use AndrewDyer\Metrics\Trend;
use DateTimeImmutable;

/**
 * Provides a concrete Trend implementation for use in tests.
 */
class TestTrendMetric extends Trend
{
    /**
     * Creates a new TestTrendMetric.
     *
     * @param DateTimeImmutable $startDate The start date of the metric range.
     * @param DateTimeImmutable $endDate The end date of the metric range.
     * @param Frequency $frequency The aggregation frequency.
     */
    public function __construct(
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
        private readonly Frequency $frequency = Frequency::Monthly,
    ) {
    }

    /**
     * Returns an empty trend result.
     *
     * @return Result The trend result.
     */
    public function calculate(): Result
    {
        return new TrendResult();
    }

    /**
     * Returns the start date of the metric range.
     *
     * @return DateTimeImmutable The start date.
     */
    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * Returns the end date of the metric range.
     *
     * @return DateTimeImmutable The end date.
     */
    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * Returns the aggregation frequency.
     *
     * @return Frequency The frequency.
     */
    public function getFrequency(): Frequency
    {
        return $this->frequency;
    }
}
