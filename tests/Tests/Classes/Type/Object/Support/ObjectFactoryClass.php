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

namespace Valkyrja\Tests\Classes\Type\Object\Support;

use Valkyrja\Type\Object\Enum\PropertyVisibilityFilter;
use Valkyrja\Type\Object\Factory\ObjectFactory;

final class ObjectFactoryClass extends ObjectFactory
{
    public static function exposeSanitizePropertyName(
        string $name,
        PropertyVisibilityFilter $filter = PropertyVisibilityFilter::ALL
    ): string|null {
        return parent::sanitizePropertyName($name, $filter);
    }
}
