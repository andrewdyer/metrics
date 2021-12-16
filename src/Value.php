<?php

namespace Anddye\Metrics;

use Illuminate\Database\Eloquent\Builder;

abstract class Value extends Metric
{
    /**
     * Returns an average aggregate between two dates.
     */
    public function average(Builder $query, string $column, string $dateColumn = null): Result
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

        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();

        $value = with(clone $query)
            ->whereBetween($dateColumn, [$this->getStartDate(), $this->getEndDate()])
            ->{$function}($column);

        return $this->getResult(round($value, $this->getPrecision()));
    }

    /**
     * Returns a new metric result.
     */
    private function getResult(float $value): Result
    {
        return (new Result())->setValue($value);
    }
}
