<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Anddye\Metrics\Value;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class UserGrowth extends Value
{
    private $endDate;
    private $startDate;

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = Chronos::parse($startDate);
        $this->endDate = Chronos::parse($endDate);
    }

    public function calculate(): Result
    {
        return $this->count((new User())->newQuery(), null, 'signed_up_at');
    }

    public function getEndDate(): ChronosInterface
    {
        return $this->endDate;
    }

    public function getStartDate(): ChronosInterface
    {
        return $this->startDate;
    }
}
