<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Results;

/**
 * Carries the result data for a partition metric.
 */
final class PartitionResult extends Result
{
    /**
     * Creates a new PartitionResult.
     *
     * @param array<string, int|float> $result The partition data keyed by group value.
     */
    public function __construct(
        private readonly array $result = [],
    ) {
    }

    /**
     * Returns the partition result data.
     *
     * @return array<string, int|float> The data keyed by group value.
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
