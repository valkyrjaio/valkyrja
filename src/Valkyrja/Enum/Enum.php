<?php

declare(strict_types=1);

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

/**
 * Interface Enum.
 *
 * @author Melech Mizrachi
 */
interface Enum extends JsonSerializable
{
    /**
     * Check if the set value on this enum is a valid value for the enum.
     *
     * @param mixed $value The value to check
     *
     * @return bool
     */
    public static function isValid($value): bool;

    /**
     * Get the valid values for this enum.
     *
     * @return array
     */
    public static function validValues(): array;

    /**
     * Json serialize the enum.
     *
     * @return string
     */
    public function jsonSerialize(): string;

    /**
     * Get the enum value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the enum value.
     *
     * @param mixed $value The value to set
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setValue($value): void;

    /**
     * Get the value of the enum.
     *
     * @return string
     */
    public function __toString(): string;
}
