<?php

declare(strict_types=1);

namespace AndrewDyer\Metrics\Tests\Unit\Results;

use AndrewDyer\Metrics\Results\PartitionResult;
use AndrewDyer\Metrics\Tests\AbstractTestCase;

/**
 * Unit tests for PartitionResult.
 */
final class PartitionResultTest extends AbstractTestCase
{
    /**
     * Asserts that getResult returns the array passed to the constructor.
     */
    public function testGetResultReturnsArray(): void
    {
        $data = ['GB' => 10, 'US' => 20];
        $result = new PartitionResult($data);

        $this->assertSame($data, $result->getResult());
    }

    /**
     * Asserts that getResult defaults to an empty array.
     */
    public function testGetResultDefaultsToEmptyArray(): void
    {
        $result = new PartitionResult();

        $this->assertSame([], $result->getResult());
    }

    /**
     * Asserts that jsonSerialize returns the expected array structure.
     */
    public function testJsonSerializeReturnsExpectedStructure(): void
    {
        $data = ['GB' => 5];
        $result = new PartitionResult($data);

        $this->assertSame(['result' => $data], $result->jsonSerialize());
    }
}
