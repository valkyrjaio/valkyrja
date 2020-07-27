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

use ArrayAccess;
use InvalidArgumentException;
use Traversable;
use Valkyrja\Config\Constants\ConfigKeyPart;

use function explode;
use function is_array;

/**
 * Class Arr.
 *
 * @author Melech Mizrachi
 */
class Arr
{
    /**
     * Get a subject value by dot notation key.
     *
     * @param array|Traversable|ArrayAccess $subject      The subject to search
     * @param string                        $key          The dot notation to search for
     * @param mixed|null                    $defaultValue The default value
     *
     * @return mixed
     */
    public static function getValueDotNotation($subject, string $key, $defaultValue = null)
    {
        if (! is_array($subject) && ! ($subject instanceof Traversable) && ! ($subject instanceof ArrayAccess)) {
            throw new InvalidArgumentException(
                'The subject must be either an array or implement the ArrayAccess, or Traversable, interface.'
            );
        }

        $value = $subject;

        // Explode the keys on period and iterate through the keys
        foreach (explode(ConfigKeyPart::SEP, $key) as $item) {
            // Trying to get the item from the current value or set the default
            $value = $value[$item] ?? null;

            // If the value is ull then the dot notation doesn't exist in this array so return the default
            if (null === $value) {
                return $defaultValue;
            }
        }

        return $value;
    }
}
