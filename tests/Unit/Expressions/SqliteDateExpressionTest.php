<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Expressions;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Expressions\SqliteDateExpression;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for SqliteDateExpression.
 */
final class SqliteDateExpressionTest extends AbstractTestCase
{
    /**
     * Asserts that the daily expression uses the correct SQLite date format.
     */
    public function testDailyExpression(): void
    {
        $expression = new SqliteDateExpression('created_at', Frequency::Daily);

        $this->assertSame("strftime('%Y-%m-%d', created_at)", $expression->getValue());
    }

    /**
     * Asserts that the weekly expression uses the correct SQLite date format.
     */
    public function testWeeklyExpression(): void
    {
        $expression = new SqliteDateExpression('created_at', Frequency::Weekly);

        $this->assertSame(
            "strftime('%Y-', created_at) || printf('%02d', (strftime('%W', created_at) + (1 - strftime('%W', strftime('%Y', created_at) || '-01-04'))))",
            $expression->getValue(),
        );
    }

    /**
     * Asserts that the monthly expression uses the correct SQLite date format.
     */
    public function testMonthlyExpression(): void
    {
        $expression = new SqliteDateExpression('created_at', Frequency::Monthly);

        $this->assertSame("strftime('%Y-%m', created_at)", $expression->getValue());
    }

    /**
     * Asserts that __toString returns the same value as getValue.
     */
    public function testToStringReturnsValue(): void
    {
        $expression = new SqliteDateExpression('created_at', Frequency::Monthly);

        $this->assertSame($expression->getValue(), (string)$expression);
    }
}
