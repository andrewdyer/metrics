<?php

namespace Anddye\Metrics;

class ValueResult extends Result
{
    /**
     * The value of the result.
     */
    private float $result;

    /**
     * Create a new value result instance.
     */
    public function __construct(float $result = 0)
    {
        $this->result = $result;
    }

    /**
     * Get the value of the result.
     */
    public function getResult(): float
    {
        return $this->result;
    }
}
