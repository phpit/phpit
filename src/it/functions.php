<?php
/**
 * (c) phpit
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace it;

/**
 * @param iterable ... $iterable
 *
 * @return \it\Iterable
 */
function it()
{
    return new Iterable(...func_get_args());
}
