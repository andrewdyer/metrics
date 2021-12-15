<?php

namespace Anddye\Metrics;

use JsonSerializable;

class Result implements JsonSerializable
{
    private $value;

    /**
     * Create a new result instance.
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * The value of the result.
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
}
