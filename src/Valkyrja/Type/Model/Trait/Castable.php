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

namespace Valkyrja\Type\Model\Trait;

use Closure;
use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Data\Cast;

use function is_array;

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
     * @inheritDoc
     *
     * @param array<string, mixed>                          $properties  The properties to set
     * @param Closure(string, mixed, Cast|null): mixed|null $modifyValue [optional] The closure to modify the value before setting
     */
    protected function internalSetProperties(array $properties, Closure|null $modifyValue = null): void
    {
        $castings = $this->internalGetCastings();

        parent::internalSetProperties(
            $properties,
            fn (string $property, mixed $value): mixed => $modifyValue !== null
                ? $modifyValue($property, $value, $castings[$property] ?? null)
                : $this->internalCheckAndCastPropertyValue(
                    $castings[$property] ?? null,
                    $value
                )
        );
    }

    /**
     * Check and cast a property's value.
     *
     * @param Cast|null $cast  The cast
     * @param mixed     $value The property value
     */
    protected function internalCheckAndCastPropertyValue(Cast|null $cast, mixed $value): mixed
    {
        // If there is no type specified or the value is null just return the value
        // cast assignment is set in the if specifically to avoid an assignment
        // if the value is null, which would be an unneeded assigned variable
        if ($value === null || $cast === null) {
            return $value;
        }

        // An array would indicate an array of types
        if ($cast->isArray && is_array($value)) {
            return array_map(
                fn (mixed $data) => $this->internalCastPropertyValue($cast, $data),
                $value
            );
        }

        return $this->internalCastPropertyValue($cast, $value);
    }

    /**
     * Cast a property's value.
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

        /** @var class-string<TypeContract> $type */
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
     * Modify the cast value before returning it.
     *
     * @param TypeContract $type The type
     */
    protected function internalModifyCastPropertyValue(TypeContract $type): TypeContract
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
