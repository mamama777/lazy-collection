<?php

declare(strict_types=1);

namespace LazyCollection;

use Generator;
use Iterator;

class RewindableGenerator implements Iterator
{
    private $generator;

    private $generatorCreator;

    public function __construct(callable $generatorCreator)
    {
        $this->generatorCreator = $generatorCreator;
        $this->generator        = $this->create();
    }

    public function current()
    {
        return $this->generator->current();
    }

    public function next(): void
    {
        $this->generator->next();
    }

    public function key(): int
    {
        return $this->generator->key();
    }

    public function valid(): bool
    {
        return $this->generator->valid();
    }

    public function rewind(): void
    {
        $this->generator = $this->create();
    }

    private function create(): Generator
    {
        return ($this->generatorCreator)();
    }
}
