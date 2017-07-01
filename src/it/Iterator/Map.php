<?php
/**
 * (c) phpit
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace it\Iterator;

/**
 * @property-read mixed $value
 * @property-read mixed $key
 * @property-read iterable $children
 */
class Map
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param mixed ... $value
     * @param mixed ... $key
     * @param iterable ... $children
     */
    public function __construct()
    {
        $args = func_get_args();
        if (isset($args[0])) {
            $this->andValue($args[0]);
        }
        if (isset($args[1])) {
            $this->andKey($args[1]);
        }
        if (isset($args[2])) {
            $this->andChildren($args[2]);
        }
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function andValue($value)
    {
        $this->properties['value'] = $value;
        return $this;
    }


    /**
     * @param mixed $key
     *
     * @return $this
     */
    public function andKey($key)
    {
        $this->properties['key'] = $key;
        return $this;
    }

    /**
     * @param iterable $children
     *
     * @return $this
     */
    public function andChildren($children)
    {
        $this->properties['children'] = $children;
        return $this;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->properties[$property]) || array_key_exists($property, $this->properties);
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
        if (in_array($property, ['value', 'key', 'children'])) {
            return null;
        }
        throw new \InvalidArgumentException($property);
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value)
    {
        throw new \InvalidArgumentException($property);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        throw new \InvalidArgumentException($property);
    }

    /**
     * @return static
     */
    public static function skip()
    {
        static $skip;
        if (!$skip) {
            $skip = new static();
        }
        return $skip;
    }

    /**
     * @return static
     */
    public static function stop()
    {
        static $stop;
        if (!$stop) {
            $stop = new static();
        }
        return $stop;
    }
}