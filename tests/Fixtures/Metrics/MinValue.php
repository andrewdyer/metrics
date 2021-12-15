<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\Measurement;
use Anddye\Metrics\Value;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class MinValue extends Value
{
    public function calculate(): Result
    {
        return $this->min(Measurement::class, 'weight_kg', 'time_of_measurement');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2021-12-31 23:59:00');
    }

    public function getName(): string
    {
        return 'Minimum Weight (kg) for 2021';
    }

    public function getPrecision(): int
    {
        return 0;
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2021-01-01 00:00:00');
    }
}
