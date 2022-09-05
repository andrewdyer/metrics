<?php

namespace Anddye\Metrics;

class PartitionResult extends Result
{
    /**
     * The value of the result.
     */
    private array $result;

    /**
     * Create a new value result instance.
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * Get the value of the result.
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
