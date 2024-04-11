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

namespace Valkyrja\Model\Models;

use Valkyrja\Model\Data\Cast;
use Valkyrja\Type\Type;

/**
 * Trait Castable.
 *
 * @author Melech Mizrachi
 */
trait Castable
{
    /**
     * Local cache for castings.
     *
     * <code>
     *      static::class => [
     *           // An property to be cast to a type
     *           'property_name' => new Cast(Type::class),
     *           // An property to be cast to an array of types
     *           'property_name' => new Cast(Type::class, isArray: true),
     *      ]
     * </code>
     *
     * @var array<string, array<string, Cast>>
     */
    private static array $castings = [];

    /**
     * @inheritDoc
     *
     * @return array<string, Cast>
     */
    public static function getCastings(): array
    {
        return [];
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array<string, mixed> $properties The properties to set
     *
     * @return void
     */
    protected function internalSetProperties(array $properties): void
    {
        $castings    = $this->internalGetCastings();
        $hasCastings = self::$cachedExistsValidations[static::class . 'hasCastings'] ??= ! empty($castings);

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            if ($this->hasProperty($property)) {
                // Set the property
                $this->__set(
                    $property,
                    $hasCastings
                        ? $this->internalCheckAndCastPropertyValue($castings, $property, $value)
                        : $value
                );
            }
        }

        $this->internalOriginalPropertiesSet();
    }

    /**
     * Get a property's value by the type (if type is set).
     *
     * @param array<string, Cast> $castings The castings
     * @param string              $property The property name
     * @param mixed               $value    The property value
     *
     * @return mixed
     */
    protected function internalCheckAndCastPropertyValue(array $castings, string $property, mixed $value): mixed
    {
        // If there is no type specified or the value is null just return the value
        // Castings assignment is set in the if specifically to avoid an assignment
        // if the value is null, which would be an unneeded assigned variable
        if ($value === null || ($cast = $castings[$property] ?? null) === null) {
            return $value;
        }

        // An array would indicate an array of types
        if ($cast->isArray) {
            return array_map(
                fn (mixed $data) => $this->internalCastPropertyValue($cast, $data),
                $value
            );
        }

        return $this->internalCastPropertyValue($cast, $value);
    }

    /**
     * Get a type from value given a type not identified prior.
     *
     * @param Cast  $cast  The cast for the property
     * @param mixed $value The value
     *
     * @return mixed|array|null
     */
    protected function internalCastPropertyValue(Cast $cast, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        /** @var class-string<Type> $type */
        $type = $cast->type;

        $typeInstance = ($value instanceof $type)
            ? $value
            : $type::fromValue($value);
        $typeInstance = $this->internalModifyCastPropertyValue($typeInstance);

        // Convert specifically stated types
        if ($cast->convert) {
            return $typeInstance->asValue();
        }

        return $typeInstance;
    }

    /**
     * Modify the type before returning it.
     *
     * @param Type $type The type
     *
     * @return Type
     */
    protected function internalModifyCastPropertyValue(Type $type): Type
    {
        return $type;
    }

    /**
     * Get the castings for the Model.
     *
     * @return array<string, Cast>
     */
    protected function internalGetCastings(): array
    {
        return self::$castings[static::class]
            ??= static::getCastings();
    }
}
