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
 * @return \it\it
 */
function it()
{
    return new it(...func_get_args());
}
