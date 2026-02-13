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

namespace Valkyrja\Type\Object\Factory;

use JsonException;
use stdClass;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Object\Enum\PropertyVisibilityFilter;
use Valkyrja\Type\Throwable\Exception\RuntimeException;

use function count;
use function explode;
use function get_object_vars;
use function is_object;
use function json_decode;
use function json_encode;
use function str_contains;

use const JSON_THROW_ON_ERROR;

class ObjectFactory
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
        /** @var object|scalar|null $decoded */
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

        /** @var object|scalar|array<array-key, mixed>|null $unserialized */
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
     * @param object $subject The subject object
     *
     * @return array<non-empty-string, mixed>
     */
    public static function getAllProperties(
        object $subject,
        PropertyVisibilityFilter $filter = PropertyVisibilityFilter::ALL
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
            $sanitizedKey = static::sanitizePropertyName($key, $filter);

            if ($sanitizedKey === null) {
                continue;
            }

            // Set the property and value
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
        return ArrayFactory::fromString(static::toString($subject));
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

    /**
     * Sanitize a cast array key to a property name.
     *
     * @return non-empty-string|null
     */
    protected static function sanitizePropertyName(
        string $name,
        PropertyVisibilityFilter $filter = PropertyVisibilityFilter::ALL
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
            if (static::shouldExcludeProtected($isProtected, $filter)) {
                return null;
            }

            if (static::shouldExcludePrivate($isProtected, $filter)) {
                return null;
            }
        } elseif (! $filter->shouldIncludePublic()) {
            return null;
        }

        $key          = end($keyParts);
        $isInvalidKey = static::isInvalidSanitizedKey($key);

        if ($isInvalidKey) {
            return null;
        }

        return $key;
    }

    /**
     * Determine if a property name should be excluded from the sanitized array.
     */
    protected static function shouldExcludeProtected(bool $isProtected, PropertyVisibilityFilter $filter): bool
    {
        return $isProtected && ! $filter->shouldIncludeProtected();
    }

    /**
     * Determine if a property name should be excluded from the sanitized array.
     */
    protected static function shouldExcludePrivate(bool $isProtected, PropertyVisibilityFilter $filter): bool
    {
        return ! $isProtected && ! $filter->shouldIncludePrivate();
    }

    /**
     * Determine if a sanitized property name key is invalid.
     *
     * @psalm-assert non-empty-string   $key
     *
     * @phpstan-assert non-empty-string $key
     */
    protected static function isInvalidSanitizedKey(string $key): bool
    {
        return $key === '' || $key === '\0';
    }
}
