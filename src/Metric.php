<?php

namespace Anddye\Metrics;

use JsonSerializable;

abstract class Metric implements JsonSerializable, MetricInterface
{
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
