<?php

declare(strict_types=1);

namespace LazyCollectionTests;

use LazyCollection\RewindableGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \LazyCollection\RewindableGenerator
 */
class RewindableGeneratorTest extends TestCase
{
    public function testTraversable()
    {
        $target = new RewindableGenerator(
            function () {
                yield from [1, 2, 3];
            }
        );

        $actual = [];
        foreach ($target as $value) {
            $actual[] = $value;
        }

        $this->assertSame([1, 2, 3], $actual);
    }

    public function testRewindable()
    {
        $target = new RewindableGenerator(
            function () {
                yield from [1, 2, 3];
            }
        );

        // move pointer to end
        iterator_to_array($target);

        // rewind() called internally
        $actual = iterator_to_array($target);

        $this->assertSame([1, 2, 3], $actual);
    }
}
