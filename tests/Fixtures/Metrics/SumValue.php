<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\Nutrition;
use Anddye\Metrics\Value;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class SumValue extends Value
{
    public function calculate(): Result
    {
        return $this->sum((new Nutrition())->newQuery(), 'calories', 'date');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2020-11-26 23:59:00');
    }

    public function getName(): string
    {
        return 'Total Calories for Lunch';
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2020-11-26 00:00:00');
    }
}
