<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

/**
 * Handles the shared test case bootstrapping for all metric tests.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * Builds an in-memory SQLite database connection for the test.
     */
    protected function setUp(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
