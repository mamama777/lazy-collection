<?php

declare(strict_types=1);

namespace LazyCollection;

use Iterator;

class LazyList
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

    public function toArray(): array
    {
        return iterator_to_array($this->iterator);
    }
}
