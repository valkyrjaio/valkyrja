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

/**
 * Attribute child class used for unit testing.
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class AttributeClassChildFixture extends AttributeFixture
{
    public mixed $default = null;

    public function __construct(
        int $counter,
        public string $test
    ) {
        parent::__construct($counter);
    }

    public function setDefault(mixed $default): void
    {
        $this->default = $default;
    }
}
