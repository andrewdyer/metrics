<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Values;

use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Values\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Period.
 */
final class PeriodTest extends TestCase
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
     * Asserts that fill does not skip months when the start date falls on the last day of a month.
     */
    public function testFillMonthlyDoesNotSkipMonthsOnLateStartDay(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-31'),
            new DateTimeImmutable('2026-03-31'),
            Frequency::Monthly,
        );

        $filled = $period->fill();

        $this->assertArrayHasKey('January 2026', $filled);
        $this->assertArrayHasKey('February 2026', $filled);
        $this->assertArrayHasKey('March 2026', $filled);
        $this->assertCount(3, $filled);
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
     * Asserts that formatDate returns a weekly label containing a year and range separator.
     */
    public function testFormatDateWeeklyContainsYear(): void
    {
        $period = new Period(
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-01-31'),
            Frequency::Weekly,
        );

        $formatted = $period->formatDate('2026-03');

        $this->assertStringContainsString(' - ', $formatted);
        $this->assertStringContainsString('2026', $formatted);
    }

    /**
     * Asserts that weekly labels are unique when the period spans a year boundary.
     */
    public function testWeeklyLabelsAreUniqueAcrossYearBoundary(): void
    {
        $period = new Period(
            new DateTimeImmutable('2025-12-22'),
            new DateTimeImmutable('2026-01-11'),
            Frequency::Weekly,
        );

        $filled = $period->fill();

        $this->assertCount(count(array_unique(array_keys($filled))), $filled);
    }
}
