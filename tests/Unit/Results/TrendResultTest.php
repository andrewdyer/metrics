<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Results;

use AndrewDyer\Metrics\Results\TrendResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for TrendResult.
 */
final class TrendResultTest extends AbstractTestCase
{
    /**
     * Asserts that getResult returns the array passed to the constructor.
     */
    public function testGetResultReturnsArray(): void
    {
        $data = ['January 2025' => 10, 'February 2025' => 20];
        $result = new TrendResult($data);

        $this->assertSame($data, $result->getResult());
    }

    /**
     * Asserts that getResult defaults to an empty array.
     */
    public function testGetResultDefaultsToEmptyArray(): void
    {
        $result = new TrendResult();

        $this->assertSame([], $result->getResult());
    }

    /**
     * Asserts that jsonSerialize returns the expected array structure.
     */
    public function testJsonSerializeReturnsExpectedStructure(): void
    {
        $data = ['January 2025' => 10];
        $result = new TrendResult($data);

        $this->assertSame(['result' => $data], $result->jsonSerialize());
    }
}
