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
     * Implementations must ensure __toString() returns the same value as getValue().
     *
     * @return string The SQL expression.
     */
    public function getValue(): string;
}
