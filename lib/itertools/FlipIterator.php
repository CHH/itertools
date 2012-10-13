<?php

namespace itertools;

# Internal: An iterator which yields the inner iterator's values as keys and the 
# keys as values, similar in behaviour to `array_flip()`.
class FlipIterator extends \IteratorIterator
{
    function current()
    {
        return $this->getInnerIterator()->key();
    }

    # Public: Return the inner iterator's value as key. Only scalars are 
    # valid keys in PHP arrays, so if the value isn't one, we convert it 
    # to a String.
    #
    # Make sure all your values are string serializable though.
    #
    # Returns the inner Iterator's return value of `current()`.
    function key()
    {
        $current = $this->getInnerIterator()->current();

        if (!is_scalar($current)) {
            return (string) $current;
        }

        return $current;
    }
}

