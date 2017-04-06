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

use InvalidArgumentException;

use JsonSerializable;

use ReflectionClass;

/**
 * Class Enum
 *
 * @package Valkyrja\Enum
 *
 * @author  Melech Mizrachi
 */
abstract class Enum implements JsonSerializable
{
    /**
     * The value of this enum.
     *
     * @var string
     */
    protected $value;

    /**
     * The enum cache to avoid more than one reflection class per enum.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * The allowable enum values.
     *
     * @var array
     */
    protected const VALUES = null;

    /**
     * Enum constructor.
     *
     * @param mixed $value The value to set
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Set the enum value.
     *
     * @param mixed $value The value to set
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setValue($value): void
    {
        // If the value is not valid
        if (! static::isValid($value)) {
            // Throw an exception
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid enumeration %s for Enum %s',
                    $value,
                    get_class($this)
                )
            );
        }

        $this->value = $value;
    }

    /**
     * Get the enum value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Check if the set value on this enum is a valid value for the enum
     *
     * @param mixed $value The value to check
     *
     * @return bool
     */
    public static function isValid($value): bool
    {
        // If the value is a set value in the enum
        if (in_array($value, static::validValues(), true)) {
            // It is valid!
            return true;
        }

        return false;
    }

    /**
     * Get the valid values for this enum.
     *
     * @return array
     */
    public static function validValues(): array
    {
        // Get the class name that was called
        $className = get_called_class();

        // If the called enum isn't yet cached
        // and the values aren't already set (to avoid a reflection class)
        if (! array_key_exists($className, self::$cache) && null === static::VALUES) {
            // Get a reflection class of the enum
            $reflectionClass = new ReflectionClass($className);

            // Set the cache to avoid a reflection class creation on each new instance of the enum
            self::$cache[$className] = array_values($reflectionClass->getConstants());
        }

        return static::VALUES ?? self::$cache[$className] ?? [];
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
     * Get the value of the enum.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
