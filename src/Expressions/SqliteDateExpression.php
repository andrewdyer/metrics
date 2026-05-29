<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Expressions;

use AndrewDyer\Metrics\Contracts\DateExpression;
use AndrewDyer\Metrics\Enums\Frequency;

/**
 * Builds a SQLite-compatible date expression for grouping by frequency.
 */
final readonly class SqliteDateExpression implements DateExpression
{
    /**
     * Creates a new SqliteDateExpression.
     *
     * @param string $column The column name to apply the expression to.
     * @param Frequency $frequency The aggregation frequency.
     */
    public function __construct(
        private string    $column,
        private Frequency $frequency,
    ) {
    }

    /**
     * Returns the SQLite date format expression string.
     *
     * @return string The SQL expression.
     */
    public function getValue(): string
    {
        return match ($this->frequency) {
            Frequency::Daily => "strftime('%Y-%m-%d', {$this->column})",
            Frequency::Weekly => "strftime('%G-%V', {$this->column})",
            Frequency::Monthly => "strftime('%Y-%m', {$this->column})",
        };
    }

    /**
     * Returns the string representation of the date expression.
     *
     * @return string The SQL expression.
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
