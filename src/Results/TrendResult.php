<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Results;

/**
 * Carries the result data for a trend metric.
 */
final class TrendResult extends Result
{
    /**
     * Creates a new TrendResult.
     *
     * @param array<string, int|float> $result The trend data keyed by date label.
     */
    public function __construct(
        private readonly array $result = [],
    ) {
    }

    /**
     * Returns the trend result data.
     *
     * @return array<string, int|float> The data keyed by date label.
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
