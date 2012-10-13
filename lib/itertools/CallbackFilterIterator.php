<?php

namespace itertools;

# Internal: Iterator which filters out elements when the provided 
# callback returns True for the current value. This is a "polyfill" for 
# PHP versions < 5.3.0, but is included in PHP since 5.4.0.
class CallbackFilterIterator extends \FilterIterator
{
    protected $callback;

    function __construct(\Iterator $iterator, $callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("Invalid callback");
        }

        $this->callback = $callback;

        parent::__construct($iterator);
    }

    function accept()
    {
        $inner = $this->getInnerIterator();

        return call_user_func_array($this->callback, array(
            $inner->current(), $inner->key(), $inner
        ));
    }
}
