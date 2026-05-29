<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Support\Metrics\Partition;

use AndrewDyer\Metrics\Partition;
use AndrewDyer\Metrics\Results\PartitionResult;
use AndrewDyer\Metrics\Results\Result;
use DateTimeImmutable;

/**
 * Provides a concrete Partition implementation for use in tests.
 */
class TestPartitionMetric extends Partition
{
    /**
     * Creates a new TestPartitionMetric.
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
     * Returns an empty partition result.
     *
     * @return Result The partition result.
     */
    public function calculate(): Result
    {
        return new PartitionResult();
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
