<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Attribute;

use Valkyrja\Reflection\Trait\ReflectionProperty;

/**
 * Attribute class used for unit testing.
 *
 * @author Melech Mizrachi
 */
#[\Attribute(\Attribute::TARGET_ALL | \Attribute::IS_REPEATABLE)]
class Attribute
{
    use ReflectionProperty;

    public function __construct(
        public int $counter
    ) {
    }
}
