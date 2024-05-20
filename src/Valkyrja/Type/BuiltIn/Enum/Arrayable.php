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

namespace Valkyrja\Type\BuiltIn\Enum;

use Valkyrja\Type\BuiltIn\Enum\Support\Enum;

/**
 * Trait Arrayable.
 *
 * @author Melech Mizrachi
 */
trait Arrayable
{
    /**
     * Get enum case names.
     *
     * @return array
     */
    public static function names(): array
    {
        return Enum::names(static::class);
    }

    /**
     * Get enum case values.
     *
     * @return array
     */
    public static function values(): array
    {
        return Enum::values(static::class);
    }

    /**
     * Get enum as an array with name as index and value as value.
     *
     * @return array
     */
    public static function asArray(): array
    {
        return Enum::asArray(static::class);
    }

    /**
     * Get enum as an array with value as index and name as value.
     *
     * @return array
     */
    public static function asReverseArray(): array
    {
        return Enum::asReverseArray(static::class);
    }
}
