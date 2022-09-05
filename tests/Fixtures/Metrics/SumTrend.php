<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\Measurement;
use Anddye\Metrics\Trend;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class SumTrend extends Trend
{
    public function calculate(): Result
    {
        return $this->sum((new Measurement())->newQuery(), 'weight_kg', 'date');
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2020-06-30 23:59:00');
    }

    public function getFrequency(): string
    {
        return 'monthly';
    }

    public function getName(): string
    {
        return '';
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2020-04-01 00:00:00');
    }
}
