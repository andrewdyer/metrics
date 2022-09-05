<?php

namespace Anddye\Metrics;

use JsonSerializable;

abstract class Result implements JsonSerializable
{
    /**
     * Get the value of the result.
     */
    abstract public function getResult();

    /**
     * Prepare the metric result for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'result' => $this->getResult(),
        ];
    }
}
