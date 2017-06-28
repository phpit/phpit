<?php
/**
 * (c) phpit
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace it;

/**
 */
class IterableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function providerGetIterator()
    {
        return [
            'associative arrays => array_merge' => [
                'expected' => ['a' => 'a', 'c' => 'C', 'b' => 'B'],
                'args'     => [['a' => 'a', 'c' => 'c'], new \ArrayObject(['c' => 'C', 'b' => 'B'])],
            ],
            'keys as integer and as strings => mixed merge' => [
                'expected' => ['a', '0' => 'b', '1' => 'c', 'd'],
                'args'     => [['a', '0' => 'b'], ['1' => 'c', 'd']],
            ],
            'three numeric iterables => numeric merge' => [
                'expected' => ['A', 'B', 'c', 'd'],
                'args'     => [
                    \SplFixedArray::fromArray(['a', 'b', 'c']),
                    ['a', 'B'],
                    new \ArrayObject(['A', 3 => 'd']),
                ],
            ],
            'two numeric arrays => numeric merge' => [
                'expected' => ['a', 'B', 'c'],
                'args'     => [['a', 'b', 'c'], ['a', 'B']],
            ],
            'one numeric array => no merge' => [
                'expected' => ['a', 'b'],
                'args'     => [['a', 'b']],
            ],
            'empty arguments => empty result' => [
                'expected' => [],
                'args'     => [[], new \ArrayIterator()],
            ],
            'no arguments => empty result' => [
                'expected' => [],
                'args'     => [],
            ],
        ];
    }

    /**
     * @dataProvider providerGetIterator
     *
     * @param array $expected
     * @param array $args
     */
    public function testGetIterator(array $expected, array $args)
    {
        $this->assertEquals($expected, iterator_to_array(new Iterable(...$args)));
    }
}