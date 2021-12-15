<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Anddye\Metrics\Value;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class CountValue extends Value
{
    public function calculate(): Result
    {
        return $this->count((new User())->newQuery(), null, 'signed_up_at');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2020-05-31 23:59:00');
    }

    public function getName(): string
    {
        return 'User Growth';
    }

    public function getPrecision(): int
    {
        return 0;
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2020-05-01 00:00:00');
    }
}
