<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Metrics\Value;

use AndrewDyer\Metrics\Results\Result;
use AndrewDyer\Metrics\Results\ValueResult;
use AndrewDyer\Metrics\Value;
use DateTimeImmutable;

/**
 * Provides a concrete Value implementation for use in tests.
 */
class TestValueMetric extends Value
{
    /**
     * Creates a new TestValueMetric.
     *
     * @param DateTimeImmutable $startDate The start date of the metric range.
     * @param DateTimeImmutable $endDate The end date of the metric range.
     */
    public function __construct(
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
    ) {
    }

    /**
     * Returns an empty value result.
     *
     * @return Result The value result.
     */
    public function calculate(): Result
    {
        return new ValueResult(0);
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
}
