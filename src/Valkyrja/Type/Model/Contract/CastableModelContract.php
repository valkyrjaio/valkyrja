<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Contract;

use Valkyrja\Type\Data\Cast;

interface CastableModelContract extends ModelContract
{
    /**
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // A property to be cast to a type
     *          'property_name' => new Cast(Type::class),
     *          // A property to be cast to an array of types
     *          'property_name' => new Cast(Type::class, isArray: true),
     *          // A property to be cast to a type and not auto converted to an atomic type
     *          'property_name' => new Cast(Type::class, convert: false),
     *      ]
     * </code>
     *
     * @return array<string, Cast>
     */
    public static function getCastings(): array;
}
