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

use Valkyrja\Config\Constants\ConfigKeyPart;

use function explode;

/**
 * Class Arr.
 *
 * @author Melech Mizrachi
 */
class Arr
{
    /**
     * Get an array value by dot notation key.
     *
     * @param array      $array   The array to search
     * @param string     $key     The dot notation to search for
     * @param mixed|null $default The default value
     *
     * @return mixed
     */
    public static function getValueDotNotation(array $array, string $key, $default = null)
    {
        $value = $array;

        // Explode the keys on period and iterate through the keys
        foreach (explode(ConfigKeyPart::SEP, $key) as $item) {
            // Trying to get the item from the current value or set the default
            $value = $value[$item] ?? null;

            // If the value is ull then the dot notation doesn't exist in this array so return the default
            if (null === $value) {
                return $default;
            }
        }

        return $value;
    }
}
