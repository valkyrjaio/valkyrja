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

namespace Valkyrja\Support\Type;

use Valkyrja\Support\Exceptions\InvalidClassProvidedException;

use function is_a;

/**
 * Class Cls.
 *
 * @author Melech Mizrachi
 */
class Cls
{
    /**
     * Validate that a class::name inherits from another class::name.
     *
     * @param string $object   The object name to validate
     * @param string $inherits The inherits class name
     *
     * @throws InvalidClassProvidedException
     *
     * @return void
     */
    public static function validateInherits(string $object, string $inherits): void
    {
        if (! static::inherits($object, $inherits)) {
            throw new InvalidClassProvidedException('');
        }
    }

    /**
     * Check if a class::name inherits from another class::name.
     *
     * @param string $object   The object name to check
     * @param string $inherits The inherits class name
     *
     * @return bool
     */
    public static function inherits(string $object, string $inherits): bool
    {
        return is_a($object, $inherits, true);
    }
}
