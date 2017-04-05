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
        if (! static::isValid($value)) {
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
        if (! in_array($value, static::validValues(), true)) {
            return false;
        }

        return true;
    }

    /**
     * Get the valid values for this enum.
     *
     * @return array
     */
    public static function validValues(): array
    {
        $className = get_called_class();

        if (! array_key_exists($className, self::$cache)) {
            $reflectionClass = new ReflectionClass($className);

            self::$cache[$className] = array_values($reflectionClass->getConstants());
        }

        return self::$cache[$className];
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
