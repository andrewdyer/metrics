<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Contracts;

use DateTimeImmutable;

/**
 * Defines the contract for retrieving a start and end date range.
 */
interface HasDateRange
{
    /**
     * Returns the start date of the range.
     *
     * @return DateTimeImmutable The start date.
     */
    public function getStartDate(): DateTimeImmutable;

    /**
     * Returns the end date of the range.
     *
     * @return DateTimeImmutable The end date.
     */
    public function getEndDate(): DateTimeImmutable;
}
