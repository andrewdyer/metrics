<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics;

use AndrewDyer\Metrics\Contracts\HasDateRange;
use AndrewDyer\Metrics\Results\ValueResult;
use Illuminate\Database\Eloquent\Builder;

/**
 * Handles value metric calculations over a date range.
 */
abstract class Value extends Metric implements HasDateRange
{
    /**
     * Returns a value result with the average of a column over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to average.
     * @param string|null $dateColumn The date column to filter by.
     * @return ValueResult The value result.
     */
    public function average(Builder $query, string $column, ?string $dateColumn = null): ValueResult
    {
        return $this->aggregate($query, 'avg', $column, $dateColumn);
    }

    /**
     * Returns a value result with the count of records over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string|null $column The column to count; defaults to the primary key.
     * @param string|null $dateColumn The date column to filter by.
     * @return ValueResult The value result.
     */
    public function count(Builder $query, ?string $column = null, ?string $dateColumn = null): ValueResult
    {
        return $this->aggregate($query, 'count', $column, $dateColumn);
    }

    /**
     * Returns a value result with the maximum of a column over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to find the maximum of.
     * @param string|null $dateColumn The date column to filter by.
     * @return ValueResult The value result.
     */
    public function max(Builder $query, string $column, ?string $dateColumn = null): ValueResult
    {
        return $this->aggregate($query, 'max', $column, $dateColumn);
    }

    /**
     * Returns a value result with the minimum of a column over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to find the minimum of.
     * @param string|null $dateColumn The date column to filter by.
     * @return ValueResult The value result.
     */
    public function min(Builder $query, string $column, ?string $dateColumn = null): ValueResult
    {
        return $this->aggregate($query, 'min', $column, $dateColumn);
    }

    /**
     * Returns a value result with the sum of a column over the date range.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The column to sum.
     * @param string|null $dateColumn The date column to filter by.
     * @return ValueResult The value result.
     */
    public function sum(Builder $query, string $column, ?string $dateColumn = null): ValueResult
    {
        return $this->aggregate($query, 'sum', $column, $dateColumn);
    }

    /**
     * Processes an aggregate query and returns a value result.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $function The SQL aggregate function (e.g. count, sum, avg).
     * @param string|null $column The column to aggregate; defaults to the primary key.
     * @param string|null $dateColumn The date column to filter by; only applied when timestamps are enabled or an explicit column is given.
     * @return ValueResult The value result.
     */
    private function aggregate(Builder $query, string $function, ?string $column = null, ?string $dateColumn = null): ValueResult
    {
        $model = $query->getModel();
        $column = $column ?? $model->getQualifiedKeyName();

        $resolvedDateColumn = $dateColumn ?? ($model->usesTimestamps() ? $model->getQualifiedCreatedAtColumn() : null);

        $baseQuery = clone $query;

        if ($resolvedDateColumn !== null) {
            $baseQuery->whereBetween($resolvedDateColumn, [$this->getStartDate(), $this->getEndDate()]);
        }

        $value = $baseQuery->{$function}($column);

        if ($value === null) {
            return new ValueResult(0);
        }

        $rounded = round((float)$value, $this->getRoundingPrecision(), $this->getRoundingMode());

        return new ValueResult(
            $this->getRoundingPrecision() === 0 ? (int)$rounded : $rounded,
        );
    }

    /**
     * Returns a JSON-serializable representation of the value metric.
     *
     * @return array<string, mixed> The serialized value metric data.
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'dates' => [
                'start' => $this->getStartDate()->format('Y-m-d'),
                'end' => $this->getEndDate()->format('Y-m-d'),
            ],
        ]);
    }
}
