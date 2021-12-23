<?php

namespace Anddye\Metrics;

interface TrendInterface
{
    /**
     * Get the frequency of the metric.
     */
    public function getFrequency(): string;
}
