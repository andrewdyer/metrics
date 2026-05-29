<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Values;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Tests\AbstractTestCase;
use AndrewDyer\Metrics\Values\Period;
use DateTimeImmutable;

/**
 * Unit tests for Period.
 */
final class PeriodTest extends AbstractTestCase
{
    /**
     * Asserts that fill generates the correct monthly date labels mapped to zero.
     */
    public function testFillGeneratesMonthlyDates(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $filled = $period->fill();

        $this->assertArrayHasKey('January 2026', $filled);
        $this->assertArrayHasKey('February 2026', $filled);
        $this->assertArrayHasKey('March 2026', $filled);
        $this->assertSame(0, $filled['January 2026']);
    }

    /**
     * Asserts that fill generates the correct daily date labels mapped to zero.
     */
    public function testFillGeneratesDailyDates(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-03'),
            Frequency::Daily,
        );

        $filled = $period->fill();

        $this->assertArrayHasKey('January 1, 2026', $filled);
        $this->assertArrayHasKey('January 2, 2026', $filled);
        $this->assertArrayHasKey('January 3, 2026', $filled);
        $this->assertCount(3, $filled);
    }

    /**
     * Asserts that fill generates weekly date labels all mapped to zero.
     */
    public function testFillGeneratesWeeklyDates(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-31'),
            Frequency::Weekly,
        );

        $filled = $period->fill();

        $this->assertNotEmpty($filled);

        foreach ($filled as $value) {
            $this->assertSame(0, $value);
        }
    }

    /**
     * Asserts that formatDate returns the correct label for monthly frequency.
     */
    public function testFormatDateMonthly(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $this->assertSame('January 2026', $period->formatDate('2026-01'));
        $this->assertSame('March 2026', $period->formatDate('2026-03'));
    }

    /**
     * Asserts that formatDate returns the correct label for daily frequency.
     */
    public function testFormatDateDaily(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-31'),
            Frequency::Daily,
        );

        $this->assertSame('January 15, 2026', $period->formatDate('2026-01-15'));
    }

    /**
     * Asserts that formatDate returns a label containing a week range separator.
     */
    public function testFormatDateWeekly(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-31'),
            Frequency::Weekly,
        );

        $formatted = $period->formatDate('2026-03');

        $this->assertStringContainsString(' - ', $formatted);
    }
}
