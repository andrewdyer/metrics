<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Expressions;

use AndrewDyer\Metrics\Contracts\DateExpression;
use AndrewDyer\Metrics\Enums\Frequency;

/**
 * Builds a MySQL-compatible date expression for grouping by frequency.
 */
final readonly class MySqlDateExpression implements DateExpression
{
    /**
     * Creates a new MySqlDateExpression.
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
     * Returns the MySQL date format expression string.
     *
     * @return string The SQL expression.
     */
    public function getValue(): string
    {
        return match ($this->frequency) {
            Frequency::Daily => "date_format({$this->column}, '%Y-%m-%d')",
            Frequency::Weekly => "date_format({$this->column}, '%x-%v')",
            Frequency::Monthly => "date_format({$this->column}, '%Y-%m')",
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
