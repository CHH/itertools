<?php

namespace itertools;

# Internal: Iterator which passes all current values through the 
# provided callback, and yields the value returned by the callback. 
# Behaves in a similar way to `array_map()`.
class MappingIterator extends \MultipleIterator
{
    /** @var callable */
    protected $callback;

    /**
     * Constructor
     *
     * @throws \InvalidArgumentException When argument is not valid.
     *
     * @param callable $callback Gets called for each element and gets passed the current elements
     * of all iterators as arguments.
     * @param array $iterators Array of iterators, which should be concurrently traversed.
     */
    function __construct($callback, array $iterators)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Invalid callback');
        }

        $this->callback = $callback;

        foreach ($iterators as $it) {
            $this->attachIterator($it);
        }

        parent::__construct();
    }

    function current()
    {
        return call_user_func_array($this->callback, parent::current());
    }

    function key()
    {
        return current(parent::key());
    }
}
