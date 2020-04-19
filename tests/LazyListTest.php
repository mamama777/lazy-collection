<?php

declare(strict_types=1);

namespace LazyCollection\Tests;

use LazyCollection\LazyList;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \LazyCollection\LazyList
 */
class LazyListTest extends TestCase
{
    public function testRange()
    {
        $start = 1;
        $end   = 3;
        $step  = 2;

        $this->assertSame(
            [1, 3],
            LazyList::range($start, $end, $step)->toArray()
        );
    }

    public function testFilter()
    {
        $target         = LazyList::range(1, 5);
        $isEvenCallback = function ($v) {
            return $v & 1 === 1;
        };

        $this->assertSame(
            [1, 3, 5],
            $target->filter($isEvenCallback)->toArray()
        );
    }

    public function testReusable()
    {
        $target = LazyList::range(1, 3);

        // first time
        $target->toArray();

        $this->assertSame(
            [1, 2, 3],
            // second time
            $target->toArray()
        );
    }
}