<?php

namespace Anddye\Metrics\Tests;

use Anddye\Metrics\Tests\Fixtures\Metrics\AverageValue;
use Anddye\Metrics\Tests\Fixtures\Metrics\CountValue;

final class MetricTest extends AbstractTestCase
{
    public function testCanGetDescription(): void
    {
        $metric = new AverageValue();

        $description = $metric->getDescription();

        $this->assertEquals('Metric to calculate average user weight (kg).', $description);
    }

    public function testHasDefaultDescriptionIfNotSet(): void
    {
        $metric = new CountValue('2020-05-01 00:00:00', '2020-05-31 23:59:00');

        $description = $metric->getDescription();

        $this->assertEquals('', $description);
    }
}
