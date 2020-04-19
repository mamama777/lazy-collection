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

    public function testFill()
    {
        $target = LazyList::fill(3, 'a');

        $this->assertSame(
            ['a', 'a', 'a'],
            $target->toArray()
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

    public function testMap()
    {
        $target         = LazyList::range(1, 3);
        $squareCallback = function ($v) {
            return $v ** 2;
        };

        $this->assertSame(
            [1, 4, 9],
            $target->map($squareCallback)->toArray()
        );
    }

    public function testReduce()
    {
        $target      = LazyList::range(1, 3);
        $sumCallback = function ($carry, $value) {
            return $carry + $value;
        };

        $this->assertSame(
            16,
            $target->reduce($sumCallback, 10)
        );
    }

    public function testSlice()
    {
        $target = LazyList::range(1, 5);

        $this->assertSame(
            [2, 3, 4],
            $target->slice(1, 3)->toArray()
        );

        $this->assertSame(
            [2, 3, 4],
            $target->slice(-4, 3)->toArray()
        );

        $this->assertSame(
            [1, 2, 3],
            $target->slice(0, -2)->toArray()
        );

        $this->assertSame(
            [3, 4],
            $target->slice(-3, -1)->toArray()
        );
    }

    public function testCount()
    {
        $target = LazyList::range(1, 3);

        $this->assertSame(
            3,
            $target->count()
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

    public function testTraversable()
    {
        $target = LazyList::range(1, 3);

        $result = [];
        foreach ($target as $value) {
            $result[] = $value;
        }

        $this->assertSame(
            [1, 2, 3],
            $result
        );
    }

    public function testNestedLoop()
    {
        $target = LazyList::range(1, 3);

        $result = [];
        foreach ($target as $value) {
            foreach ($target as $value) {
                $result[] = $value;
            }
        }

        $this->assertSame(
            [1, 2, 3, 1, 2, 3, 1, 2, 3],
            $result
        );
    }
}
