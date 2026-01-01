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

namespace Valkyrja\Type\Model\Contract;

use Valkyrja\Type\Data\Cast;

/**
 * Interface CastableModelContract.
 *
 * @author Melech Mizrachi
 */
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
