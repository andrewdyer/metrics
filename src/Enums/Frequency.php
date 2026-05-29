<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Enums;

/**
 * Represents the supported time frequencies for trend metric aggregation.
 */
enum Frequency: string
{
    /**
     * Daily frequency.
     */
    case Daily = 'daily';

    /**
     * Weekly frequency.
     */
    case Weekly = 'weekly';

    /**
     * Monthly frequency.
     */
    case Monthly = 'monthly';
}
