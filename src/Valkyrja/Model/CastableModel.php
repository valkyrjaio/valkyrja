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

namespace Valkyrja\Model;

use Valkyrja\Model\Enums\CastType;

/**
 * Interface CastableModel.
 *
 * @author Melech Mizrachi
 */
interface CastableModel extends Model
{
    /**
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // An property to be json_decoded to an array
     *          'property_name' => CastType::array,
     *          // An property to be unserialized to an object
     *          'property_name' => CastType::object,
     *          // An property to be json_decoded to an object
     *          'property_name' => CastType::json,
     *          // An property to be cast to an string
     *          'property_name' => CastType::string,
     *          // An property to be cast to an int
     *          'property_name' => CastType::int,
     *          // An property to be cast to an float
     *          'property_name' => CastType::float,
     *          // An property to be cast to an bool
     *          'property_name' => CastType::bool,
     *          // An property to be cast to an enum
     *          'property_name' => [CastType::enum, Enum::class],
     *          // An property to be cast to an array of enums
     *          'property_name' => [CastType::enum, Enum::class],
     *          // An property to be cast to a model
     *          'property_name' => [CastType::model, Model::class],
     *          // An property to be cast to an array of models
     *          'property_name' => [CastType::model, [Model::class]],
     *          // An property to be cast to a Type
     *          'property_name' => [CastType::type, Type::class],
     *      ]
     * </code>
     *
     * @return array<string, CastType|array{0:CastType, 1:class-string|array{0:class-string}}>
     */
    public static function getCastings(): array;

    /**
     * Allowed classes for serialization of object type properties.
     *
     * <code>
     *      [
     *          // An array of allowed classes for serialization for object types
     *          'property_name' => [ClassName::class],
     *      ]
     * </code>
     *
     * @return array<string, class-string[]>
     */
    public static function getCastingsAllowedClasses(): array;
}
