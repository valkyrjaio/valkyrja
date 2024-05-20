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

namespace Valkyrja\Type\Attribute;

use Attribute;
use Valkyrja\Type\Contract\Type as TypeContract;

/**
 * Attribute ArrayShape.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL)]
class ArrayShape
{
    /**
     * @param array<string|int, TypeContract|Union|Intersection> $shape
     */
    public function __construct(
        public array $shape = [],
    ) {
    }
}
