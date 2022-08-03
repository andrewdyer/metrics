<?php

namespace Anddye\Metrics;

class ValueResult extends Result
{
    /**
     * The value of the result.
     */
    private float $value;

    /**
     * Create a new value result instance.
     */
    public function __construct(float $value = 0)
    {
        $this->value = $value;
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
            'value' => $this->value
        ];
    }
}
