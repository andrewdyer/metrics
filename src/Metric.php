<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics;

use AndrewDyer\Metrics\Results\Result;
use JsonSerializable;
use ReflectionClass;

/**
 * Handles the base behaviour and serialization for all metric types.
 */
abstract class Metric implements JsonSerializable
{
    /**
     * Calculates and returns the metric result.
     *
     * @return Result The computed result.
     */
    abstract public function calculate(): Result;

    /**
     * Returns the short class name of the metric.
     *
     * @return string The metric name.
     */
    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * Returns the metric description.
     *
     * @return string The description.
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * Returns the rounding precision applied to metric values.
     *
     * @return int The number of decimal places.
     */
    public function getRoundingPrecision(): int
    {
        return 0;
    }

    /**
     * Returns the PHP rounding mode constant used for calculations.
     *
     * @return int The PHP rounding mode constant.
     */
    public function getRoundingMode(): int
    {
        return PHP_ROUND_HALF_UP;
    }

    /**
     * Returns a JSON-serializable representation of the metric.
     *
     * @return array<string, mixed> The serialized metric data.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }
}
