<?php

namespace Anddye\Metrics;

use JsonSerializable;

class Result implements JsonSerializable
{
    private $value;

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
     * Set the value of the result.
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
