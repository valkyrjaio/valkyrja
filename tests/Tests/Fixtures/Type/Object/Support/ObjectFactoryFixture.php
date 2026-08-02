<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Object\Support;

use Valkyrja\Type\Object\Enum\PropertyVisibilityFilter;
use Valkyrja\Type\Object\Factory\ObjectFactory;

final class ObjectFactoryFixture extends ObjectFactory
{
    public static function exposeSanitizePropertyName(
        string $name,
        PropertyVisibilityFilter $filter = PropertyVisibilityFilter::ALL
    ): string|null {
        return parent::sanitizePropertyName($name, $filter);
    }
}
