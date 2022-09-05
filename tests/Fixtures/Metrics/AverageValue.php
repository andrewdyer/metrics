<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\Measurement;
use Anddye\Metrics\Value;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class AverageValue extends Value
{
    public function calculate(): Result
    {
        return $this->average(Measurement::where('user_id', 1), 'weight_kg', 'date');
    }

    public function getDescription(): string
    {
        return 'Metric to calculate average user weight (kg).';
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2020-05-31 23:59:00');
    }

    public function getName(): string
    {
        return 'Average Weight (kg) for May 2020';
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2020-05-01 00:00:00');
    }
}
