<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics;

use AndrewDyer\Metrics\Contracts\HasDateRange;
use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Factories\DateExpressionFactory;
use AndrewDyer\Metrics\Results\TrendResult;
use AndrewDyer\Metrics\Values\Period;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use InvalidArgumentException;

/**
 * Handles trend metric calculations over a date range at a given frequency.
 */
abstract class Trend extends Metric implements HasDateRange
{
    /**
     * Returns the frequency used for trend aggregation.
     *
     * @return Frequency The aggregation frequency.
     */
    abstract public function getFrequency(): Frequency;

    /**
     * Returns a trend result with averaged values over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to average.
     * @param string|null $dateColumn The date column to group by.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    public function average(Builder $query, string $column, ?string $dateColumn = null): TrendResult
    {
        return $this->aggregate($query, 'avg', $column, $dateColumn);
    }

    /**
     * Returns a trend result with record counts over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string|null $column The column to count; defaults to the primary key.
     * @param string|null $dateColumn The date column to group by.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    public function count(Builder $query, ?string $column = null, ?string $dateColumn = null): TrendResult
    {
        return $this->aggregate($query, 'count', $column, $dateColumn);
    }

    /**
     * Returns a trend result with maximum values over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to find the maximum of.
     * @param string|null $dateColumn The date column to group by.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    public function max(Builder $query, string $column, ?string $dateColumn = null): TrendResult
    {
        return $this->aggregate($query, 'max', $column, $dateColumn);
    }

    /**
     * Returns a trend result with minimum values over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to find the minimum of.
     * @param string|null $dateColumn The date column to group by.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    public function min(Builder $query, string $column, ?string $dateColumn = null): TrendResult
    {
        return $this->aggregate($query, 'min', $column, $dateColumn);
    }

    /**
     * Returns a trend result with summed values over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to sum.
     * @param string|null $dateColumn The date column to group by.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    public function sum(Builder $query, string $column, ?string $dateColumn = null): TrendResult
    {
        return $this->aggregate($query, 'sum', $column, $dateColumn);
    }

    /**
     * Processes an aggregate trend query and returns a trend result.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $function The SQL aggregate function (e.g. count, sum, avg).
     * @param string|null $column The column to aggregate; defaults to the primary key.
     * @param string|null $dateColumn The date column to group by; defaults to created_at when timestamps are enabled, or throws when no column can be resolved.
     * @return TrendResult The trend result.
     * @throws InvalidArgumentException When no date column can be resolved for a model without timestamps.
     */
    private function aggregate(Builder $query, string $function, ?string $column = null, ?string $dateColumn = null): TrendResult
    {
        $model = $query->getModel();
        $column = $column ?? $model->getQualifiedKeyName();
        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        $resolvedDateColumn = $dateColumn ?? ($model->usesTimestamps() ? $model->getQualifiedCreatedAtColumn() : null);

        if ($resolvedDateColumn === null) {
            throw new InvalidArgumentException(
                sprintf(
                    'A date column must be specified for [%s] because it does not use timestamps.',
                    $model::class,
                ),
            );
        }

        $period = new Period($this->getStartDate(), $this->getEndDate(), $this->getFrequency());
        $expression = DateExpressionFactory::create($query, $resolvedDateColumn, $this->getFrequency());

        $results = (clone $query)
            ->select(new Expression("{$expression} as date, {$function}({$wrappedColumn}) as aggregate"))
            ->whereBetween($resolvedDateColumn, [$this->getStartDate(), $this->getEndDate()])
            ->groupBy(new Expression($expression->getValue()))
            ->orderBy('date')
            ->get();

        $data = array_merge(
            $period->fill(),
            $results->mapWithKeys(function($result) use ($period) {
                if ($result->date === null) {
                    return [];
                }

                return [
                    $period->formatDate($result->date) => round(
                        $result->aggregate,
                        $this->getRoundingPrecision(),
                        $this->getRoundingMode(),
                    ),
                ];
            })->all(),
        );

        return new TrendResult($data);
    }

    /**
     * Returns a JSON-serializable representation of the trend metric.
     *
     * @return array<string, mixed> The serialized trend metric data.
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'dates' => [
                'start' => $this->getStartDate()->format('Y-m-d'),
                'end' => $this->getEndDate()->format('Y-m-d'),
            ],
            'frequency' => $this->getFrequency(),
        ]);
    }
}
