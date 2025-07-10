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

namespace Valkyrja\Type\BuiltIn\Support;

use ArrayAccess;
use JsonException;
use Stringable;
use Valkyrja\Type\Exception\RuntimeException;

use function array_filter;
use function explode;
use function is_array;
use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

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
     * @param ArrayAccess<string, mixed>|array<string, mixed> $subject      The subject to search
     * @param string                                          $key          The dot notation to search for
     * @param mixed|null                                      $defaultValue [optional] The default value
     * @param non-empty-string                                $separator    [optional] The separator
     *
     * @phpstan-param ArrayAccess<string, mixed>|array<string, mixed> $subject      The subject
     *
     * @return mixed
     */
    public static function getValueDotNotation(
        ArrayAccess|array $subject,
        string $key,
        mixed $defaultValue = null,
        string $separator = '.'
    ): mixed {
        $value    = $subject;
        $keyParts = explode($separator, $key);

        // Explode the keys on period and iterate through the keys
        foreach ($keyParts as $item) {
            if (! is_array($value) && ! $value instanceof ArrayAccess) {
                return $defaultValue;
            }

            /** @var mixed $value */
            // Trying to get the item from the current value or set the default
            $value = $value[$item] ?? null;

            // If the value is ull then the dot notation doesn't exist in this array so return the default
            if ($value === null) {
                return $defaultValue;
            }
        }

        return $value;
    }

    /**
     * Convert an array to a string.
     *
     * @param array<array-key, mixed> $subject The subject array
     *
     * @throws JsonException
     *
     * @return string
     */
    public static function toString(array $subject): string
    {
        return json_encode($subject, JSON_THROW_ON_ERROR);
    }

    /**
     * Un-convert an array from a string.
     *
     * @param string $subject The subject array as a string
     *
     * @throws JsonException
     *
     * @return array<array-key, mixed>
     */
    public static function fromString(string $subject): array
    {
        $decoded = json_decode($subject, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new RuntimeException("Invalid json string provided: `$subject`");
        }

        return $decoded;
    }

    /**
     * Strip out null valued properties in an array.
     *
     * @param array<array-key, mixed> $subject The subject array
     *
     * @return array<array-key, mixed>
     */
    public static function newWithoutNull(array $subject): array
    {
        $new = $subject;

        return static::withoutNull($new);
    }

    /**
     * Strip out null valued properties in an array.
     *
     * @param array<array-key, mixed> $subject The subject array
     *
     * @return array<array-key, mixed>
     */
    public static function withoutNull(array &$subject): array
    {
        /**
         * @var array-key $key
         * @var mixed     $value
         */
        foreach ($subject as $key => &$value) {
            if (is_array($value)) {
                static::withoutNull($value);
            }

            if ($value === null) {
                unset($subject[$key]);
            }
        }

        return $subject;
    }

    /**
     * Filter empty strings.
     *
     * @param Stringable|string ...$strings
     *
     * @return array<Stringable|string>
     */
    public static function filterEmptyStrings(Stringable|string ...$strings): array
    {
        return array_filter($strings, static fn (Stringable|string $string): bool => ((string) $string) !== '');
    }
}
