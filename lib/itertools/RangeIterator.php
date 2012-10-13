<?php

namespace itertools;

class RangeIterator implements \Iterator
{
    protected $start;
    protected $end;
    protected $step;
    protected $position;

    function __construct($start, $end, $step = 1)
    {
        $this->start = $this->position = $start;
        $this->end = $end;
        $this->step = $step;
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->start + ($this->position * $this->step);
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        $this->position++;
    }

    function valid()
    {
        return $this->current() <= $this->end;
    }
}
