<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Attributes\Classes;

/**
 * Attribute class used for unit testing.
 *
 * @author Melech Mizrachi
 */
#[\Attribute(\Attribute::TARGET_ALL | \Attribute::IS_REPEATABLE)]
class Attribute
{
    public function __construct(
        public int $counter
    ) {
    }
}
