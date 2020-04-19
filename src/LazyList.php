<?php

declare(strict_types=1);

namespace LazyCollection;

use Iterator;
use IteratorAggregate;
use Traversable;

class LazyList implements IteratorAggregate
{
    private $iterator;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public static function range(
        int $start,
        int $end,
        int $step = 1
    ) {
        $generator = function () use ($start, $end, $step) {
            for ($i = $start; $i <= $end; $i += $step) {
                yield $i;
            }
        };

        return static::fromGenerator($generator);
    }

    public static function fill(int $num, $value): self
    {
        $generator = function () use ($num, $value) {
            for (; $num > 0; --$num) {
                yield $value;
            }
        };

        return static::fromGenerator($generator);
    }

    public static function fromGenerator(
        callable $generatorCreator
    ): self {
        return new static(new RewindableGenerator($generatorCreator));
    }

    public function filter(callable $callback): self
    {
        $generator = function () use ($callback) {
            foreach ($this->iterator as $value) {
                if ($callback($value)) {
                    yield $value;
                }
            }
        };

        return static::fromGenerator($generator);
    }

    public function map(callable $callback): self
    {
        $generator = function () use ($callback) {
            foreach ($this->iterator as $value) {
                yield $callback($value);
            }
        };

        return static::fromGenerator($generator);
    }

    public function reduce(callable $callback, $initial = null)
    {
        $carry = $initial;

        foreach ($this->iterator as $value) {
            $carry = $callback($carry, $value);
        }

        return $carry;
    }

    public function slice(int $offset, int $length = 0): self
    {
        $generator = function () use ($offset, $length) {
            if ($offset < 0 || $length < 0) {
                yield from array_slice(
                    $this->toArray(),
                    $offset,
                    $length
                );

                return;
            }

            for (; $offset > 0; --$offset) {
                $this->iterator->next();
            }
            for (; $length > 0; --$length) {
                yield $this->iterator->current();
                $this->iterator->next();
            }
        };

        return static::fromGenerator($generator);
    }

    public function count(): int
    {
        return iterator_count($this->iterator);
    }

    public function toArray(): array
    {
        return iterator_to_array($this->iterator);
    }

    public function getIterator(): Traversable
    {
        return clone $this->iterator;
    }
}
