<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Enum;

use Exception;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;

/**
 * Class Enum.
 *
 * @author Melech Mizrachi
 */
abstract class Enum implements JsonSerializable
{
    /**
     * The allowable enum values.
     *
     * @var array
     */
    protected const VALUES = null;

    /**
     * The enum cache to avoid more than one reflection class per enum.
     *
     * @var array
     */
    protected static $cache = [];
    /**
     * The value of this enum.
     *
     * @var string
     */
    protected $value;

    /**
     * Enum constructor.
     *
     * @param mixed $value The value to set
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Check if the set value on this enum is a valid value for the enum.
     *
     * @param mixed $value The value to check
     *
     * @return bool
     */
    public static function isValid($value): bool
    {
        if (is_array($value) || is_object($value)) {
            return false;
        }

        // Get the valid values to compare with
        $validValues = static::validValues();

        // If the value isset in the valid values array and the value matches
        // the value to check
        // ?? Why is this here ??
        // As is known by all isset is faster than in_array. We want to
        // capitalize on that with some enums by making the const VALUES
        // array a key value pair of the value itself so that we've
        // essentially got value => value for each item in the const VALUES
        // array. This way we can take advantage of the quickness of isset over
        // in_array. However, because not all enums may do this we need to
        // ensure if the value isset as the key in the array that it also
        // matches as the value of that item in the array, otherwise we'll
        // get false positives where its a normal array of 0 => value, 1 =>
        // value and we check for 0 being a valid value where it may very
        // well not be valid at all.
        if (isset($validValues[$value]) && $validValues[$value] === $value) {
            return true;
        }

        return in_array($value, $validValues, true);
    }

    /**
     * Get the valid values for this enum.
     *
     * @return array
     */
    public static function validValues(): array
    {
        // If the const VALUES array has been populated
        if (null !== static::VALUES) {
            // Use it as the developer took the time to define it
            return static::VALUES;
        }

        // Get the class name that was called
        $className = static::class;

        // If the called enum isn't yet cached
        // and the values aren't already set (to avoid a reflection class)
        if (! array_key_exists($className, self::$cache)) {
            // Set the cache to avoid a reflection class creation on each new
            // instance of the enum
            self::$cache[$className] = static::reflectionValidValues();
        }

        return self::$cache[$className] ?? [];
    }

    /**
     * Get the reflection valid values.
     *
     * @return array
     */
    protected static function reflectionValidValues(): array
    {
        try {
            // Get a reflection class of the enum
            $values = (new ReflectionClass(static::class))->getConstants();
        } // Catch any exceptions
        catch (Exception $exception) {
            $values = [];
        }

        $validValues = [];

        // Iterate through the values
        foreach ($values as $key => $value) {
            // If this value is defined in this abstract Enum (self)
            if (defined(self::class . '::' . $key)) {
                // Unset it from the list as its not a valid Enum value, but
                // rather a value the Enum class needs (like self::VALUES)
                unset($values[$key]);

                continue;
            }

            $validValues[$value] = $value;
        }

        return $validValues;
    }

    /**
     * Json serialize the enum.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getValue();
    }

    /**
     * Get the enum value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the enum value.
     *
     * @param mixed $value The value to set
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setValue($value): void
    {
        // If the value is not valid
        if (! static::isValid($value)) {
            // Throw an exception
            throw new InvalidArgumentException(sprintf('Invalid enumeration %s for Enum %s', $value, get_class($this)));
        }

        $this->value = $value;
    }

    /**
     * Get the value of the enum.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
