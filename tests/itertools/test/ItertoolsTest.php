<?php

namespace itertools\test;

use itertools;
use ArrayIterator;

class ItertoolsTest extends \PHPUnit_Framework_TestCase
{
    function testMap()
    {
        $a = range(1, 5);

        $this->assertEquals(
            array(2, 4, 6, 8, 10),
            iterator_to_array(itertools\map(
                function($it) { return 2 * $it; },
                itertools\to_iterator($a)
            ))
        );
    }

    function testMapMulti()
    {
        $a = array(1, 2, 3);
        $b = array("one", "two", "three");

        $this->assertEquals(
            array(
                array(1, "one"), array(2, "two"), array(3, "three")
            ),
            iterator_to_array(
                itertools\map(
                    function($a, $b) { return array($a, $b); },
                    itertools\to_iterator($a), itertools\to_iterator($b)
                )
            )
        );
    }

    function testFilter()
    {
        $a = itertools\xrange(1, 10);

        $this->assertEquals(
            range(1, 7),
            iterator_to_array(
                itertools\filter($a, function($it) { return $it <= 7; }), false
            )
        );
    }

    function testFilterWithoutArgumentRemovesFalsyElements()
    {
        $a = new ArrayIterator(array(1, 2, null, 3, 0, false));

        $this->assertEquals(
            array(1, 2, 3),
            iterator_to_array(
                itertools\filter($a),
                false
            )
        );
    }

    function testWalk()
    {
        $a = itertools\xrange(1, 7);
        $numbers = array();

        itertools\walk($a, function($number) use (&$numbers) {
            $numbers[] = $number;
        });

        $this->assertEquals(range(1, 7), $numbers);
    }
}
