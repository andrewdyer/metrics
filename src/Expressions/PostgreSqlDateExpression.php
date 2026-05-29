<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Expressions;

use AndrewDyer\Metrics\Contracts\DateExpression;
use AndrewDyer\Metrics\Enums\Frequency;

/**
 * Builds a PostgreSQL-compatible date expression for grouping by frequency.
 */
final readonly class PostgreSqlDateExpression implements DateExpression
{
    /**
     * Creates a new PostgreSqlDateExpression.
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
     * Returns the PostgreSQL date format expression string.
     *
     * @return string The SQL expression.
     */
    public function getValue(): string
    {
        return match ($this->frequency) {
            Frequency::Daily => "to_char({$this->column}, 'YYYY-MM-DD')",
            Frequency::Weekly => "to_char({$this->column}, 'IYYY-IW')",
            Frequency::Monthly => "to_char({$this->column}, 'YYYY-MM')",
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
