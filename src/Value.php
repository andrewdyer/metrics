<?php

namespace Anddye\Metrics;

abstract class Value extends Metric
{
    /**
     * Returns an average aggregate between two dates.
     */
    public function average(string $model, string $column, string $dateColumn = null): Result
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn);
    }

    /**
     * Returns a count aggregate between two dates.
     */
    public function count(string $model, ?string $column = null, ?string $dateColumn = null): Result
    {
        return $this->aggregate($model, 'count', $column, $dateColumn);
    }

    /**
     * Returns a maximum aggregate between two dates.
     */
    public function max(string $model, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($model, 'max', $column, $dateColumn);
    }

    /**
     * Returns a minimum aggregate between two dates.
     */
    public function min(string $model, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($model, 'min', $column, $dateColumn);
    }

    /**
     * Returns a sum aggregate between two dates.
     */
    public function sum(string $model, string $column, ?string $dateColumn = null): Result
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn);
    }

    /**
     * Returns a result showing the growth of a model between two dates.
     */
    private function aggregate(string $model, string $function, ?string $column = null, ?string $dateColumn = null): Result
    {
        $query = (new $model())->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();

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
        return new Result($value);
    }
}
