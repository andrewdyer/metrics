<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics;

use AndrewDyer\Metrics\Contracts\HasDateRange;
use AndrewDyer\Metrics\Results\PartitionResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Handles partition metric calculations grouped by a specified column.
 */
abstract class Partition extends Metric implements HasDateRange
{
    /**
     * Returns a partition result with record counts grouped by the specified column.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $groupBy The column to group results by.
     * @param string|null $column The column to count; defaults to the primary key.
     * @param string|null $dateColumn The date column to filter by; defaults to created_at.
     * @return PartitionResult The partition result.
     */
    public function count(Builder $query, string $groupBy, ?string $column = null, ?string $dateColumn = null): PartitionResult
    {
        return $this->aggregate($query, 'count', $groupBy, $column, $dateColumn);
    }

    /**
     * Returns a partition result with summed values grouped by the specified column.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $groupBy The column to group results by.
     * @param string $column The column to sum.
     * @param string|null $dateColumn The date column to filter by; defaults to created_at.
     * @return PartitionResult The partition result.
     */
    public function sum(Builder $query, string $groupBy, string $column, ?string $dateColumn = null): PartitionResult
    {
        return $this->aggregate($query, 'sum', $groupBy, $column, $dateColumn);
    }

    /**
     * Returns a partition result with averaged values grouped by the specified column.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $groupBy The column to group results by.
     * @param string $column The column to average.
     * @param string|null $dateColumn The date column to filter by; defaults to created_at.
     * @return PartitionResult The partition result.
     */
    public function average(Builder $query, string $groupBy, string $column, ?string $dateColumn = null): PartitionResult
    {
        return $this->aggregate($query, 'avg', $groupBy, $column, $dateColumn);
    }

    /**
     * Processes an aggregate query and returns a partition result.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $function The SQL aggregate function (e.g. count, sum, avg).
     * @param string $groupBy The column to group results by.
     * @param string|null $column The column to aggregate; defaults to the primary key.
     * @param string|null $dateColumn The date column to filter by; defaults to created_at.
     * @return PartitionResult The partition result.
     */
    private function aggregate(Builder $query, string $function, string $groupBy, ?string $column = null, ?string $dateColumn = null): PartitionResult
    {
        $column = $column ?? $query->getModel()->getQualifiedKeyName();
        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);
        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();

        $results = (clone $query)
            ->select($groupBy, new Expression("{$function}({$wrappedColumn}) as aggregate"))
            ->whereBetween($dateColumn, [$this->getStartDate(), $this->getEndDate()])
            ->groupBy($groupBy)
            ->get();

        $segments = explode('.', $groupBy);
        $key = end($segments);

        $data = $results->mapWithKeys(function($result) use ($key) {
            return [$result->{$key} => $result->aggregate];
        })->all();

        return new PartitionResult($data);
    }

    /**
     * Returns a JSON-serializable representation of the partition metric.
     *
     * @return array<string, mixed> The serialized partition metric data.
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
