<?php

namespace Anddye\Metrics;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Builder;

abstract class Partition extends Metric
{
    /**
     * Returns a count aggregate between two dates.
     */
    public function count(Builder $query, string $groupBy, ?string $column = null): PartitionResult
    {
        return $this->aggregate($query, 'count', $column, $groupBy);
    }

    /**
     * Returns a partition result showing the segments of an aggregate.
     */
    private function aggregate(Builder $query, string $function, ?string $column, string $groupBy): PartitionResult
    {
        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        $results = $query->select(
            $groupBy, DB::raw("{$function}({$wrappedColumn}) as aggregate")
        )->groupBy($groupBy)->get();

        return $this->getResult($results->mapWithKeys(function ($result) use ($groupBy) {
            return $this->formatAggregateResult($result, $groupBy);
        })->all());
    }

    /**
     * Format the aggregate result for the partition.
     */
    private function formatAggregateResult($result, string $groupBy): array
    {
        $key = $result->{last(explode('.', $groupBy))};

        return [$key => $result->aggregate];
    }

    /**
     * Create a new partition metric result.
     */
    private function getResult(array $result): PartitionResult
    {
        return new PartitionResult($result);
    }
}
