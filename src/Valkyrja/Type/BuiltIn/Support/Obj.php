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

use JsonException;
use stdClass;
use Valkyrja\Type\Throwable\Exception\RuntimeException;

use function count;
use function explode;
use function get_object_vars;
use function is_object;
use function json_decode;
use function json_encode;
use function str_contains;

use const JSON_THROW_ON_ERROR;

class Obj
{
    /**
     * Convert an object to a string.
     *
     * @param object $subject The subject object
     *
     * @throws JsonException
     */
    public static function toString(object $subject): string
    {
        return json_encode($subject, JSON_THROW_ON_ERROR);
    }

    /**
     * Un-convert an object from a string.
     *
     * @param string $subject The subject object as a string
     *
     * @throws JsonException
     */
    public static function fromString(string $subject): object
    {
        /** @var mixed $decoded */
        $decoded = json_decode($subject, false, 512, JSON_THROW_ON_ERROR);

        if (! is_object($decoded)) {
            throw new RuntimeException("Invalid json string provided: `$subject`");
        }

        return $decoded;
    }

    /**
     * Convert an object to a serialized string.
     *
     * @param object $subject The subject object
     */
    public static function toSerializedString(object $subject): string
    {
        return serialize($subject);
    }

    /**
     * Un-convert an object from a serialized string.
     *
     * @param string              $subject        The subject object as a string
     * @param class-string[]|null $allowedClasses The allowed classes to be unserialized
     */
    public static function fromSerializedString(string $subject, array|null $allowedClasses = [stdClass::class]): object
    {
        $options = [];

        if ($allowedClasses !== null) {
            $options['allowed_classes'] = $allowedClasses;
        }

        /** @var mixed $unserialized */
        $unserialized = unserialize($subject, $options);

        if (! is_object($unserialized)) {
            throw new RuntimeException("Invalid serialized string provided: `$subject`");
        }

        return $unserialized;
    }

    /**
     * Get the object's publicly accessible properties.
     *
     * @param object $subject The subject object
     *
     * @return array<string, mixed>
     */
    public static function getProperties(object $subject): array
    {
        return get_object_vars($subject);
    }

    /**
     * Get all object's properties regardless of visibility.
     *
     * @param object $subject          The subject object
     * @param bool   $includeProtected [optional] Whether to include protected members
     * @param bool   $includePrivate   [optional] Whether to include private members
     *
     * @return array<array-key, mixed>
     */
    public static function getAllProperties(
        object $subject,
        bool $includeProtected = true,
        bool $includePrivate = true
    ): array {
        /** @var array<string, mixed> $castSubject */
        // The subject cast as an array
        $castSubject = (array) $subject;
        // The array to return
        $array = [];

        // Iterate through each subject
        /**
         * @var string $key
         * @var mixed  $value
         */
        foreach ($castSubject as $key => $value) {
            $sanitizedKey = static::sanitizePropertyName($key, $includeProtected, $includePrivate);

            if ($sanitizedKey === null) {
                continue;
            }

            // Set the property and value
            /** @psalm-suppress MixedAssignment */
            $array[$sanitizedKey] = $value;
        }

        return $array;
    }

    /**
     * Convert an object to a deep array.
     *
     * @param object $subject The subject object
     *
     * @throws JsonException
     *
     * @return array<array-key, mixed>
     */
    public static function toDeepArray(object $subject): array
    {
        return Arr::fromString(static::toString($subject));
    }

    /**
     * Get a subject value by dot notation name.
     *
     * @param object           $subject   The subject to search
     * @param non-empty-string $name      The dot notation to search for
     * @param mixed|null       $default   [optional] The default value
     * @param non-empty-string $separator [optional] The separator
     */
    public static function getValueDotNotation(object $subject, string $name, mixed $default = null, string $separator = '.'): mixed
    {
        $value = $subject;
        $parts = explode($separator, $name);

        // Explode the keys on period and iterate through the keys
        foreach ($parts as $item) {
            if (! is_object($value)) {
                return $default;
            }

            /** @var mixed $value */
            // Trying to get the item from the current value or set the default
            $value = $value->$item ?? null;

            // If the value is ull then the dot notation doesn't exist in this array so return the default
            if ($value === null) {
                return $default;
            }
        }

        return $value;
    }

    protected static function sanitizePropertyName(
        string $name,
        bool $includeProtected = true,
        bool $includePrivate = true
    ): string|null {
        // Explode the key on the \0 character
        /*
         * Public members: member_name
         * Protected members: \0*\0member_name
         * Private members: \0Class_name\0member_name
         */
        $isProtected = str_contains($name, "\0*");
        $keyParts    = explode("\0", $name);

        if (count($keyParts) > 1) {
            if (! $includeProtected && $isProtected) {
                return null;
            }

            if (! $includePrivate && ! $isProtected) {
                return null;
            }
        }

        return end($keyParts);
    }

    protected static function sanitizeCastObjectKey(string $key): string|null
    {
        return $key;
    }
}
