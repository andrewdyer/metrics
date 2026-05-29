<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Contracts;

/**
 * Defines the contract for generating a database-specific date expression string.
 */
interface DateExpression
{
    /**
     * Returns the raw SQL date expression string.
     *
     * @return string The SQL expression.
     */
    public function getValue(): string;

    /**
     * Returns the string representation of the date expression.
     *
     * @return string The SQL expression.
     */
    public function __toString(): string;
}
