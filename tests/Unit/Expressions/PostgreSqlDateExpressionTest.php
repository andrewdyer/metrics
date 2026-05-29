<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Expressions;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Expressions\PostgreSqlDateExpression;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for PostgreSqlDateExpression.
 */
final class PostgreSqlDateExpressionTest extends AbstractTestCase
{
    /**
     * Asserts that the daily expression uses the correct PostgreSQL date format.
     */
    public function testDailyExpression(): void
    {
        $expression = new PostgreSqlDateExpression('created_at', Frequency::Daily);

        $this->assertSame("to_char(created_at, 'YYYY-MM-DD')", $expression->getValue());
    }

    /**
     * Asserts that the weekly expression uses the correct PostgreSQL date format.
     */
    public function testWeeklyExpression(): void
    {
        $expression = new PostgreSqlDateExpression('created_at', Frequency::Weekly);

        $this->assertSame("to_char(created_at, 'IYYY-IW')", $expression->getValue());
    }

    /**
     * Asserts that the monthly expression uses the correct PostgreSQL date format.
     */
    public function testMonthlyExpression(): void
    {
        $expression = new PostgreSqlDateExpression('created_at', Frequency::Monthly);

        $this->assertSame("to_char(created_at, 'YYYY-MM')", $expression->getValue());
    }

    /**
     * Asserts that __toString returns the same value as getValue.
     */
    public function testToStringReturnsValue(): void
    {
        $expression = new PostgreSqlDateExpression('created_at', Frequency::Monthly);

        $this->assertSame($expression->getValue(), (string)$expression);
    }
}
