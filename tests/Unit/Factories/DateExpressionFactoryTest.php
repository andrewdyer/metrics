<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Factories;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Expressions\SqliteDateExpression;
use AndrewDyer\Metrics\Factories\DateExpressionFactory;
use AndrewDyer\Metrics\Tests\AbstractTestCase;
use AndrewDyer\Metrics\Tests\Support\Models\Order;

/**
 * Unit tests for DateExpressionFactory.
 */
final class DateExpressionFactoryTest extends AbstractTestCase
{
    /**
     * Asserts that create returns a SqliteDateExpression when the SQLite driver is active.
     */
    public function testCreateReturnsSqliteExpressionForSqliteDriver(): void
    {
        $expression = DateExpressionFactory::create(
            Order::query(),
            'created_at',
            Frequency::Monthly,
        );

        $this->assertInstanceOf(SqliteDateExpression::class, $expression);
    }

    /**
     * Asserts that DateExpressionFactory cannot be instantiated.
     */
    public function testCannotBeInstantiated(): void
    {
        $reflection = new \ReflectionClass(DateExpressionFactory::class);

        $this->assertTrue($reflection->getConstructor()->isPrivate());
    }
}
