<?php

namespace Anddye\Metrics;

class TrendResult extends Result
{
    /**
     * The value of the result.
     */
    private array $trend;

    /**
     * Create a new value result instance.
     */
    public function __construct(array $trend = [])
    {
        $this->trend = $trend;
    }

    /**
     * Get the value of the result.
     */
    public function getTrend(): array
    {
        return $this->trend;
    }

    /**
     * Prepare the metric result for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'trend' => $this->trend
        ];
    }
}
