<?php

namespace Anddye\Metrics;

use Cake\Chronos\ChronosInterface;
use JsonSerializable;
use ReflectionClass;

abstract class Metric implements JsonSerializable
{
    /**
     * Calculate the value of the metric.
     */
    abstract public function calculate(): Result;

    /**
     * Get the description of the metric.
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * Get the end date used when calculating the value of the metric.
     */
    abstract public function getEndDate(): ChronosInterface;

    /**
     * Get the name of the metric.
     */
    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * Get the rounding mode for the metric.
     */
    public function getRoundingMode(): int
    {
        return PHP_ROUND_HALF_UP;
    }

    /**
     * Get the number of decimal digits used when rounding the value.
     */
    public function getRoundingPrecision(): int
    {
        return 0;
    }

    /**
     * Get the start date used when calculating the value of the metric.
     */
    abstract public function getStartDate(): ChronosInterface;

    /**
     * Prepare the metric for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'dates' => [
                'start' => $this->getStartDate(),
                'end' => $this->getEndDate(),
            ],
        ];
    }
}
