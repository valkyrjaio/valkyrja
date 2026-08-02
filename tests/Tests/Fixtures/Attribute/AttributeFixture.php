<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Attribute;

use Attribute;
use Valkyrja\Attribute\Contract\ReflectionAwareAttributeContract;
use Valkyrja\Attribute\Trait\ReflectionAwareAttribute;

/**
 * Attribute class used for unit testing.
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class AttributeFixture implements ReflectionAwareAttributeContract
{
    use ReflectionAwareAttribute;

    public function __construct(
        public int $counter
    ) {
    }
}
