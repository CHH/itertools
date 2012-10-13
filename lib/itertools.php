<?php

namespace itertools;

use Traversable;
use itertools\FlipIterator;
use itertools\MappingIterator;
use itertools\RangeIterator;

/**
 * Turns any value into an Iterator.
 *
 * @param mixed $value Iterators are returned as is, Arrays are wrapped in
 * ArrayIterators, plain Traversables are wrapped in an IteratorIterator,
 * everything else is casted to "array" and wrapped in an ArrayIterator.
 *
 * @return \Iterator
 */
function to_iterator($value)
{
    if ($value instanceof \Iterator) {
        return $value;
    } else if ($value instanceof \Traversable) {
        return new \IteratorIterator($value);
    } else {
        return new \ArrayIterator((array) $value);
    }
}

/**
 * Returns an Iterator which flips keys and values.
 *
 * @param Traversable $iterable
 *
 * @return \itertools\FlipIterator
 */
function flip(Traversable $iterable)
{
    return new FlipIterator($iterable);
}

/**
 * Returns an Iterator which runs all yielded values through the callback function
 * and yields the return value as current value.
 *
 * @param callable $callback
 * @param Traversable $iterable,... One or more iterators, like in array_map()
 *
 * @return \itertools\MappingIterator
 */
function map($callback)
{
    $iterators = array_slice(func_get_args(), 1);

    return new MappingIterator($callback, $iterators);
}

/**
 * Returns an Iterator which yields numbers from start to (and including) end,
 * and increments by the given step.
 *
 * @param int $start First number to yield from the iterator.
 * @param int $end Last number to yield from the iterator.
 * @param mixed $step Increment between steps, can be any number.
 *
 * @return \itertools\RangeIterator
 */
function xrange($start, $end, $step = 1)
{
    return new RangeIterator($start, $end, $step);
}

/**
 * Returns an Iterator which only yields elements for which the callback
 * function returns True.
 *
 * @param Traversable $iterable
 * @param callable $callback Optional, callback which gets passed the current element
 * and should return True when the element should stay in the array. When omitted an Iterator
 * is returned which omits all "falsey" values, just like array_filter() without callback.
 *
 * @return \CallbackFilterIterator | \itertools\CallbackFilterIterator Depending on the PHP version.
 */
function filter(Traversable $iterable, $callback = null)
{
    if ($callback === null) {
        $callback = function($val) {
            return (bool) $val;
        };
    }

    if (class_exists('\\CallbackFilterIterator')) {
        return new \CallbackFilterIterator($iterable, $callback);
    } else {
        return new \itertools\CallbackFilterIterator($iterable, $callback);
    }
}

/**
 * Slices the traversable element.
 *
 * @param Traversable $iterable
 * @param int $offset At which offset to begin slicing.
 * @param int $count Optional, length of the slice.
 *
 * @return \LimitIterator
 */
function slice(Traversable $iterable, $offset, $count = -1)
{
    return new \LimitIterator($iterable, $offset, $count);
}

/**
 * Walks through all elements of the iterator and calls the callback function.
 *
 * @param \Iterator $iterable Iterator to walk through.
 * @param callable $callback Callback which gets called with the current element, the key
 * and the iterator instance.
 *
 * @return void
 */
function walk(\Iterator $iterable, $callback)
{
    iterator_apply($iterable, function() use ($iterable, $callback) {
        call_user_func_array($callback, array(
            $iterable->current(), $iterable->key(), $iterable
        ));

        # iterator_apply only keeps iterating when the callback returns 'true'
        return true;
    });
}

