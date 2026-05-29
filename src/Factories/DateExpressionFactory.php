<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Factories;

use AndrewDyer\Metrics\Contracts\DateExpression;
use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Expressions\MySqlDateExpression;
use AndrewDyer\Metrics\Expressions\PostgreSqlDateExpression;
use AndrewDyer\Metrics\Expressions\SqliteDateExpression;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

/**
 * Creates date expression instances based on the active database driver.
 */
final class DateExpressionFactory
{
    /**
     * Creates a date expression appropriate for the given query's database driver.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string $column The date column to wrap in the expression.
     * @param Frequency $frequency The aggregation frequency.
     * @return DateExpression The resolved date expression.
     * @throws InvalidArgumentException When the database driver is not supported.
     */
    public static function create(Builder $query, string $column, Frequency $frequency): DateExpression
    {
        $driver = $query->getConnection()->getDriverName();

        return match ($driver) {
            'mysql', 'mariadb' => new MySqlDateExpression($column, $frequency),
            'sqlite' => new SqliteDateExpression($column, $frequency),
            'pgsql' => new PostgreSqlDateExpression($column, $frequency),
            default => throw new InvalidArgumentException(
                sprintf('Database driver "%s" is not supported.', $driver),
            ),
        };
    }
}
