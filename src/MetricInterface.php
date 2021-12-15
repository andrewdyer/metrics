<?php

namespace Anddye\Metrics;

use Cake\Chronos\ChronosInterface;

interface MetricInterface
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(): Result;

    /**
     * Get the description of the metric.
     */
    public function getDescription(): string;

    /**
     * Get the end date used when calculating the value of the metric.
     */
    public function getEndDate(): ChronosInterface;

    /**
     * Get the name of the metric.
     */
    public function getName(): string;

    /**
     * Get the number of decimal digits used when rounding the value.
     */
    public function getPrecision(): int;

    /**
     * Get the start date used when calculating the value of the metric.
     */
    public function getStartDate(): ChronosInterface;
}
