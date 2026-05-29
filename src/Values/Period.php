<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Values;

use AndrewDyer\Metrics\Enums\Frequency;
use DateInterval;
use DateMalformedStringException;
use DateTimeImmutable;

/**
 * Represents a date range period and provides date label generation by frequency.
 */
final readonly class Period
{
    /**
     * Creates a new Period.
     *
     * @param DateTimeImmutable $start The start of the period.
     * @param DateTimeImmutable $end The end of the period.
     * @param Frequency $frequency The aggregation frequency.
     */
    public function __construct(
        public DateTimeImmutable $start,
        public DateTimeImmutable $end,
        public Frequency         $frequency,
    ) {
    }

    /**
     * Returns an array of date labels mapped to zero for the full period.
     *
     * @return array<string, int> The date labels with default zero values.
     * @throws DateMalformedStringException
     */
    public function fill(): array
    {
        $dates = [];
        $current = $this->normalise($this->start);

        while ($current <= $this->end) {
            $dates[$this->formatDate($current->format($this->rawFormat()))] = 0;
            $current = $current->add($this->interval());
        }

        return $dates;
    }

    /**
     * Returns a human-readable label for the given raw date string.
     *
     * @param string $date The raw date string to format.
     * @return string The formatted date label.
     * @throws DateMalformedStringException
     */
    public function formatDate(string $date): string
    {
        return match ($this->frequency) {
            Frequency::Daily => (new DateTimeImmutable($date))->format('F j, Y'),
            Frequency::Weekly => $this->formatWeek($date),
            Frequency::Monthly => (new DateTimeImmutable($date . '-01'))->format('F Y'),
        };
    }

    /**
     * Returns a formatted week range label for the given ISO year-week string.
     *
     * @param string $date The ISO year-week string (e.g. 2026-03).
     * @return string The formatted week range label.
     */
    private function formatWeek(string $date): string
    {
        [$year, $week] = explode('-', $date);

        $start = (new DateTimeImmutable())
            ->setISODate((int)$year, (int)$week)
            ->setTime(0, 0);

        $end = $start->add(new DateInterval('P6D'));

        return $start->format('F j') . ' - ' . $end->format('F j, Y');
    }

    /**
     * Returns the normalised start date for the current frequency.
     *
     * @param DateTimeImmutable $date The date to normalise.
     * @return DateTimeImmutable The normalised date.
     * @throws DateMalformedStringException
     */
    private function normalise(DateTimeImmutable $date): DateTimeImmutable
    {
        return match ($this->frequency) {
            Frequency::Monthly => $date->modify('first day of this month')->setTime(0, 0),
            default => $date,
        };
    }

    /**
     * Returns the raw date format string for the current frequency.
     *
     * @return string The PHP date format string.
     */
    private function rawFormat(): string
    {
        return match ($this->frequency) {
            Frequency::Daily => 'Y-m-d',
            Frequency::Weekly => 'o-W',
            Frequency::Monthly => 'Y-m',
        };
    }

    /**
     * Returns the date interval corresponding to the current frequency.
     *
     * @return DateInterval The interval to advance by.
     */
    private function interval(): DateInterval
    {
        return match ($this->frequency) {
            Frequency::Daily => new DateInterval('P1D'),
            Frequency::Weekly => new DateInterval('P1W'),
            Frequency::Monthly => new DateInterval('P1M'),
        };
    }
}
