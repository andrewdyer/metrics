<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Results;

/**
 * Carries the result data for a value metric.
 */
final class ValueResult extends Result
{
    /**
     * Creates a new ValueResult.
     *
     * @param int|float $result The computed metric value.
     */
    public function __construct(
        private readonly int|float $result = 0,
    ) {
    }

    /**
     * Returns the computed metric value.
     *
     * @return int|float The result.
     */
    public function getResult(): int|float
    {
        return $this->result;
    }
}
