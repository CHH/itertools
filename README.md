# itertools

`itertools` aims to bring the support for operations on PHP's iterators
to the same level than for arrays.

## Install

Install via [composer](http://getcomposer.org):

    % wget http://getcomposer.org/composer.phar
    % php composer.phar require chh/itertools:*

## Usage

`itertools` provides a small set of operations via a set of functions.
Most functions operate by wrapping an iterator in another iterator. 
This means that most operations are lazily evaluated, except where it doesn't make sense.

That makes these operations an efficient solution for filtering and mapping Data Sets or
Database Result Sets.

### `\Traversable itertools\slice(\Traversable $traversable, $start, [$count = -1])`

Wraps the traversable in an iterator, which starts iteration at the
given offset `$start`, and stops after yielding `$count` elements. By
default, it iterates to the end of the wrapped traversable.

Example:

```php
<?php

use itertools;

$users = new ArrayIterator(['John', 'Jim', 'Joe']);

foreach (itertools\slice($users, 1) as $user) {
    echo "$user\n";
}
# Output:
# Jim
# Joe
```

### `\Traversable itertools\map(callable $callback, \Traversable $traversable...)`

Returns an Iterator which yields each return value of the callback
function, when called with the current elements of each traversable. The
callback receives the current elements of all iterators, in the order
they were passed to `itertools\map()`.

Example:

```php
<?php

use itertools;

$a = new ArrayIterator(['one', 'two', 'three']);
$b = new ArrayIterator([1, 2, 3]);

$tuples = itertools\map(
    function($word, $number) {
        return [$word, $number];
    },
    $a, $b
);
# [ ['one', 1], ['two', 2], ['three', 3] ]
```

### `\Traversable itertools\filter(\Traversable $traversable, [callable $callback])`

Removes all elements, based on the return value of the callback
function, or removes all not "truthy" values when the callback was
omitted. Similar to `array_filter()`.

```php
<?php

use itertools;

$a = new ArrayIterator(range(1, 10));

var_export(iterator_to_array(
    itertools\filter($a, function($value) {
        return $value >= 7;
    })
));
# Output:
# [7, 8, 9]
```

### `\Traversable itertools\flip(\Traversable $traversable)`

Returns an Iterator which yields the inner iterator's keys in
`current()`, and the values in `key()`. Similar to `array_flip()`.
Non-scalar values are not valid PHP array keys, so make sure they're at
least string serializable.

### `\Traversable itertools\xrange($start, $end, [$step = 1])`

Returns an Iterator, which yields all numbers in the given range from
`$start` to inclusive `$end`, and increments after each iteration by the given `$step`.

This iterator is useful when having a large range to iterate through
(with large I mean a few houndreds of thousands), because it's more
memory efficient. This is because not all possible numbers must be stored in the
array.

### `void itertools\walk(\Iterator $iterator, callable $callback)`

Calls the callback function on each element of the iterator. Ignores the callback function's 
return value, unlike [`iterator_apply()`](http://php.net/iterator_apply).

### `\Traversable itertools\to_iterator($value)`

Converts any value into a valid iterator.

* Iterators are passed straight through.
* Objects implementing only `\Traversable` are wrapped in an
  `\IteratorIterator` (for example `PDOStatement`).
* Arrays are wrapped in an `ArrayIterator`.
* Everything else is cast to `array`, and then wrapped in an
  `\ArrayIterator`.

## License

Copyright (c) 2012 Christoph Hochstrasser <christoph.hochstrasser@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
