<?php
/**
 * (c) phpit
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace it\Iterator;

/**
 * @covers Mapper
 */
class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Mapper::children
     * @covers Mapper::getChildren
     * @covers Mapper::hasChildren
     * @covers Mapper::doMap
     */
    public function testRecursiveIteration()
    {
        $this->fail('todo');
    }

    /**
     * @return array
     */
    public function providerSimpleIteration()
    {
        return [
            'combine map, skip and stop' => [
                'expected' => ['directly-key' => 'directly-value', 'map-key' => 'map-value' , 3 => 'd'],
                'inner' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'mapper' => function($value, &$key) {
                    if ($value == 'e') {
                        return Map::stop();
                    }
                    if ($value == 'c') {
                        return Map::skip();
                    }
                    if ($value == 'a') {
                        $key = 'directly-key';
                        return 'directly-value';
                    }
                    if ($value == 'b') {
                        return new Map('map-value', 'map-key');
                    }

                    return $value;
                },
            ],
            'stop iteration' => [
                'expected' => ['a', 'b', 'c'],
                'inner' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'mapper' => function($value) {
                    if ($value == 'd') {
                        return Map::stop();
                    }
                    return $value;
                },
            ],
            'skip entries' => [
                'expected' => ['a', 3 => 'd', 5 => 'f'],
                'inner' => ['a', 'b', 'c', 'd', 'e', 'f'],
                'mapper' => function($value) {
                    return in_array($value, ['b', 'c', 'e']) ? Map::skip() : $value;
                },
            ],
            'map keys with Map object' => [
                'expected' => ['0-a' => 'a', '1-b' => 'b', '2-c' => 'c'],
                'inner' => ['a', 'b', 'c'],
                'mapper' => function($value, $key) {
                    return (new Map())->andKey("$key-$value");
                },
            ],
            'map keys directly' => [
                'expected' => ['0-a' => 'a', '1-b' => 'b', '2-c' => 'c'],
                'inner' => ['a', 'b', 'c'],
                'mapper' => function($value, &$key) {
                    $key = "$key-$value";
                    return $value;
                },
            ],
            'map values with Map object' => [
                'expected' => ['0-a', '1-b', '2-c'],
                'inner' => ['a', 'b', 'c'],
                'mapper' => function($value, $key) {
                    return new Map("$key-$value");
                },
            ],
            'map values directly' => [
                'expected' => ['0-a', '1-b', '2-c'],
                'inner' => ['a', 'b', 'c'],
                'mapper' => function($value, $key, Mapper $mapper) {
                    $this->assertInstanceOf(Mapper::class, $mapper);
                    return "$key-$value";
                },
            ],
            'simple iterator' => [
                'expected' => ['a' => 'a', 'b' => ['c' => 'd']],
                'inner' => new \ArrayIterator(['a' => 'a', 'b' => ['c' => 'd']]),
                'mapper' => null,
            ],
            'simple array' => [
                'expected' => ['a', 'b', 'c'],
                'inner' => ['a', 'b', 'c'],
                'mapper' => null,
            ],
            'empty array' => [
                'expected' => [],
                'inner' => [],
                'mapper' => null,
            ],
            'null => exception' => [
                'expected' => new \RuntimeException('Property $inner must be iterable'),
                'inner' => null,
                'mapper' => null,
            ],
        ];
    }

    /**
     * @dataProvider providerSimpleIteration
     *
     * @covers Mapper::rewind
     * @covers Mapper::valid
     * @covers Mapper::key
     * @covers Mapper::current
     * @covers Mapper::next
     * @covers Mapper::next
     * @covers Mapper::doMap
     *
     * @param array|\Exception $expected
     * @param iterable|\Traversable $inner
     * @param callable|null $mapper
     */
    public function testSimpleIteration($expected, $inner, $mapper)
    {
        $iterator = new Mapper($inner, $mapper);
        if ($expected instanceof \Exception) {
            $this->expectException(get_class($expected));
            $this->expectExceptionMessage($expected->getMessage());
        }
        $actual = iterator_to_array($iterator);
        $this->assertEquals($expected, $actual);
    }
}