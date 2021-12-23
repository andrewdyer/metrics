<?php

namespace Anddye\Metrics;

use JsonSerializable;

class Result implements JsonSerializable
{
    /**
     * @var array
     */
    private $trend = [];

    /**
     * @var float
     */
    private $value = 0;

    /**
     * Get the trend of data for the metric.
     */
    public function getTrend(): array
    {
        return $this->trend;
    }

    /**
     * Get the value of the result.
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Prepare the metric result for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }

    /**
     * Set the trend of data for the metric.
     */
    public function setTrend(array $trend): self
    {
        $this->trend = $trend;

        return $this;
    }

    /**
     * Set the value of the result.
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
