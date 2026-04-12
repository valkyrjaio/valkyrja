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

namespace Valkyrja\Type\Object\Support;

use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectPropertyProvidedException;
use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectProvidedException;
use Valkyrja\Type\String\Factory\StringFactory;

use function explode;
use function is_a;

class Cls
{
    /**
     * Validate that a class::name inherits from another class::name.
     *
     * @param class-string $object   The object name to check
     * @param class-string $inherits The inherits class name
     *
     * @throws InvalidObjectProvidedException
     */
    public static function validateInherits(string $object, string $inherits): void
    {
        if (! static::inherits($object, $inherits)) {
            throw new InvalidObjectProvidedException("Expected $inherits got $object");
        }
    }

    /**
     * Check if a class::name inherits from another class::name.
     *
     * @param class-string $object   The object name to check
     * @param class-string $inherits The inherits class name
     */
    public static function inherits(string $object, string $inherits): bool
    {
        return is_a($object, $inherits, true);
    }

    /**
     * Validate that a class::name has a property.
     *
     * @param class-string|string $object   The object name to validate
     * @param string              $property The property name
     *
     * @throws InvalidObjectProvidedException
     */
    public static function validateHasProperty(string $object, string $property): void
    {
        if (! static::hasProperty($object, $property)) {
            throw new InvalidObjectPropertyProvidedException("$property does not exist in $object");
        }
    }

    /**
     * Check if a class::name has a property.
     *
     * @param class-string|string $object   The object name to validate
     * @param string              $property The property name
     */
    public static function hasProperty(string $object, string $property): bool
    {
        return property_exists($object, $property);
    }

    /**
     * Get a class nice name.
     *
     * @param class-string $name The class object name
     */
    public static function getNiceName(string $name): string
    {
        return StringFactory::replace($name, '\\', '');
    }

    /**
     * Get a class name without namespace.
     *
     * @param class-string $name The class object name
     */
    public static function getName(string $name): string
    {
        $parts = explode('\\', $name);

        return end($parts);
    }
}
