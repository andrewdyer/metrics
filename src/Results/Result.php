<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Results;

use JsonSerializable;

/**
 * Carries the base behaviour and serialization for all metric results.
 */
abstract class Result implements JsonSerializable
{
    /**
     * Returns the metric result value.
     *
     * @return mixed The result.
     */
    abstract public function getResult(): mixed;

    /**
     * Returns a JSON-serializable representation of the result.
     *
     * @return array<string, mixed> The serialized result data.
     */
    public function jsonSerialize(): array
    {
        return [
            'result' => $this->getResult(),
        ];
    }
}
