<?php

namespace Anddye\Metrics\Tests\Fixtures\Metrics;

use Anddye\Metrics\Partition;
use Anddye\Metrics\Result;
use Anddye\Metrics\Tests\Fixtures\Models\User;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class UsersPerPlanPartition extends Partition
{
    public function calculate(): Result
    {
        return $this->count((new User())->newQuery(), 'stripe_plan');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getEndDate(): ChronosInterface
    {
        return Chronos::parse('2020-12-31');
    }

    public function getName(): string
    {
        return '';
    }

    public function getStartDate(): ChronosInterface
    {
        return Chronos::parse('2020-01-01');
    }
}
