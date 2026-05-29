<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Expressions;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Expressions\MySqlDateExpression;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for MySqlDateExpression.
 */
final class MySqlDateExpressionTest extends AbstractTestCase
{
    /**
     * Asserts that the daily expression uses the correct MySQL date format.
     */
    public function testDailyExpression(): void
    {
        $expression = new MySqlDateExpression('created_at', Frequency::Daily);

        $this->assertSame("date_format(created_at, '%Y-%m-%d')", $expression->getValue());
    }

    /**
     * Asserts that the weekly expression uses the correct MySQL date format.
     */
    public function testWeeklyExpression(): void
    {
        $expression = new MySqlDateExpression('created_at', Frequency::Weekly);

        $this->assertSame("date_format(created_at, '%x-%v')", $expression->getValue());
    }

    /**
     * Asserts that the monthly expression uses the correct MySQL date format.
     */
    public function testMonthlyExpression(): void
    {
        $expression = new MySqlDateExpression('created_at', Frequency::Monthly);

        $this->assertSame("date_format(created_at, '%Y-%m')", $expression->getValue());
    }

    /**
     * Asserts that __toString returns the same value as getValue.
     */
    public function testToStringReturnsValue(): void
    {
        $expression = new MySqlDateExpression('created_at', Frequency::Monthly);

        $this->assertSame($expression->getValue(), (string)$expression);
    }
}
