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
class Iterable implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * @param iterable ... $iterable
     */
    public function __construct()
    {
        $args = func_get_args();

        if (count($args) == 1 && $args[0] instanceof \Traversable) {
            $this->iterator = $args[0];
        } else if (!count($args)) {
            $this->iterator = new \SplFixedArray(0);
        } else {
            $this->iterator = $args;
        }
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        if (!$this->iterator instanceof \Traversable) {
            $merged = [];
            foreach (array_reverse($this->iterator) as $arg) {
                $merged += is_array($arg) ? $arg : iterator_to_array($arg);
            }
            $this->iterator = new \ArrayIterator($merged);
        }
        return $this->iterator;
    }
}