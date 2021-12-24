<?php

namespace Anddye\Metrics;

use Anddye\DateExpression\DateExpressionFactory;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

abstract class Trend extends Metric implements TrendInterface
{
    /**
     * Returns an average aggregate between two dates.
     */
    public function average(Builder $query, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($query, 'avg', $column, $dateColumn);
    }

    /**
     * Returns a count aggregate between two dates.
     */
    public function count(Builder $query, ?string $column = null, ?string $dateColumn = null): Result
    {
        return $this->aggregate($query, 'count', $column, $dateColumn);
    }

    /**
     * Returns a maximum aggregate between two dates.
     */
    public function max(Builder $query, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($query, 'max', $column, $dateColumn);
    }

    /**
     * Returns a minimum aggregate between two dates.
     */
    public function min(Builder $query, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($query, 'min', $column, $dateColumn);
    }

    /**
     * Returns a sum aggregate between two dates.
     */
    public function sum(Builder $query, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($query, 'sum', $column, $dateColumn);
    }

    /**
     * Returns a result showing the growth of a model between two dates.
     */
    private function aggregate(Builder $query, string $function, ?string $column = null, ?string $dateColumn = null): Result
    {
        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();

        $expression = DateExpressionFactory::create($query, $dateColumn, $this->getFrequency());

        $results = with(clone $query)
            ->select(DB::raw("{$expression} as date, {$function}({$wrappedColumn}) as aggregate"))
            ->whereBetween($dateColumn, [$this->getStartDate(), $this->getEndDate()])
            ->groupBy(DB::raw($expression))
            ->orderBy('date')
            ->get();

        return $this->getResult(array_merge($this->generateDatesAccordingToFrequency(), $results->mapWithKeys(function ($result) {
            return [$this->formatAggregateDateAccordingToFrequency($result->date) => round($result->aggregate, $this->getPrecision(), $this->getRoundingMode())];
        })->all()));
    }

    /**
     * Format the aggregate result date into a proper string for the given frequency.
     */
    private function formatAggregateDateAccordingToFrequency(string $date): string
    {
        switch ($this->getFrequency()) {
            case 'daily':
                return $this->formatDateAccordingToFrequency(Chronos::createFromFormat('Y-m-d', $date));

            case 'weekly':
                [$year, $week] = explode('-', $date);

                return $this->formatDateAccordingToFrequency((
                    new Chronos())
                        ->setISODate((int) $year, (int) $week)
                        ->setTime(0, 0));

            case 'monthly':
                [$year, $month] = explode('-', $date);

                return $this->formatDateAccordingToFrequency(Chronos::create((int) $year, (int) $month, 1));

            default:
                throw new InvalidArgumentException('Frequency not supported.');
        }
    }

    /**
     * Format the date into a proper string for the given frequency.
     */
    private function formatDateAccordingToFrequency(ChronosInterface $date): string
    {
        switch ($this->getFrequency()) {
            case 'daily':
                return $date->format('F') . ' ' . $date->format('j') . ', ' . $date->format('Y');

            case 'weekly':
                return $date->startOfWeek()->format('F') . ' ' . $date->startOfWeek()->format('j') . ' - ' .
                    $date->endOfWeek()->format('F') . ' ' . $date->endOfWeek()->format('j');

            case 'monthly':
                return $date->format('F') . ' ' . $date->format('Y');

            default:
                throw new InvalidArgumentException('Frequency not supported.');
        }
    }

    /**
     * Generate all the possible dates for the given frequency.
     */
    private function generateDatesAccordingToFrequency(): array
    {
        $nextDate = $this->getStartDate();

        $dates[$this->formatDateAccordingToFrequency($nextDate)] = 0;

        $endDate = $this->getEndDate();

        while ($nextDate->lessThan($endDate)) {
            switch ($this->getFrequency()) {
                case 'daily':
                    $nextDate = $nextDate->addDay();
                    break;

                case 'weekly':
                    $nextDate = $nextDate->addWeek();
                    break;

                case 'monthly':
                    $nextDate = $nextDate->addMonth();
                    break;

                default:
                    throw new InvalidArgumentException('Frequency not supported.');
            }

            if ($nextDate->lessThanOrEquals($endDate)) {
                $dates[$this->formatDateAccordingToFrequency($nextDate)] = 0;
            }
        }

        return $dates;
    }

    /**
     * Returns a new metric result.
     */
    private function getResult(array $result): Result
    {
        return (new Result())->setTrend($result);
    }
}
