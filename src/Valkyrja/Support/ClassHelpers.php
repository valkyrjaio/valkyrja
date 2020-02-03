<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

use Valkyrja\Support\Exceptions\InvalidClassProvidedException;

/**
 * Class ClassHelpers.
 *
 * @author Melech Mizrachi
 */
class ClassHelpers
{
    /**
     * Validate a class::name inherits from another class::name.
     *
     * @param string $object
     * @param string $className
     *
     * @throws InvalidClassProvidedException
     *
     * @return void
     */
    public static function validateClass(string $object, string $className): void
    {
        if (! static::checkClassInherits($object, $className)) {
            throw new InvalidClassProvidedException('');
        }
    }

    /**
     * Check if a class::name inherits from another class::name.
     *
     * @param string $object
     * @param string $className
     *
     * @return bool
     */
    public static function checkClassInherits(string $object, string $className): bool
    {
        return is_a($object, $className, true);
    }
}
