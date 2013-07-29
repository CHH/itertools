<?php

namespace itertools;

class ChunkingIterator extends \IteratorIterator
{
    protected $chunk = [];
    protected $steps;
    protected $endReached = false;

    function __construct(\Traversable $iterator, $chunkSize)
    {
        parent::__construct($iterator);
        $this->steps = range(1, $chunkSize);
    }

    function current()
    {
        $chunk = [];

        while (true) {
            foreach ($this->steps as $step) {
                if (!$this->getInnerIterator()->valid()) {
                    break 2;
                }

                $chunk[] = $this->getInnerIterator()->current();
                $this->getInnerIterator()->next();
            }

            return $chunk;
        }

        $this->endReached = true;

        if ($chunk) {
            return $chunk;
        }
    }

    function next()
    {
    }

    function valid()
    {
        return !$this->endReached and $this->getInnerIterator()->valid();
    }
}
