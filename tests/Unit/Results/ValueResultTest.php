<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Results;

use AndrewDyer\Metrics\Results\ValueResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for ValueResult.
 */
final class ValueResultTest extends AbstractTestCase
{
    /**
     * Asserts that getResult returns the float value passed to the constructor.
     */
    public function testGetResultReturnsFloat(): void
    {
        $result = new ValueResult(99.99);

        $this->assertSame(99.99, $result->getResult());
    }

    /**
     * Asserts that getResult returns the integer value passed to the constructor.
     */
    public function testGetResultReturnsInt(): void
    {
        $result = new ValueResult(42);

        $this->assertSame(42, $result->getResult());
    }

    /**
     * Asserts that getResult defaults to zero.
     */
    public function testGetResultDefaultsToZero(): void
    {
        $result = new ValueResult();

        $this->assertSame(0, $result->getResult());
    }

    /**
     * Asserts that jsonSerialize returns the expected array structure.
     */
    public function testJsonSerializeReturnsExpectedStructure(): void
    {
        $result = new ValueResult(42);

        $this->assertSame(['result' => 42], $result->jsonSerialize());
    }
}
