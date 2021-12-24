<?php

namespace Anddye\Metrics;

use JsonSerializable;

abstract class Metric implements JsonSerializable, MetricInterface
{
    /**
     * Get the rounding mode for the metric.
     */
    public function getRoundingMode(): int
    {
        return PHP_ROUND_HALF_UP;
    }

    /**
     * Prepare the metric for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'dates' => [
                'start' => $this->getStartDate(),
                'end' => $this->getEndDate(),
            ],
        ];
    }
}
