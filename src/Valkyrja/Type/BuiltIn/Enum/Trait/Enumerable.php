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

namespace Valkyrja\Type\BuiltIn\Enum\Trait;

use BackedEnum;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Throwable\Exception\RuntimeException;

use function is_int;
use function is_string;

trait Enumerable
{
    /**
     * Get a new Type given a value.
     */
    public static function fromValue(mixed $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        static::validateValue($value);

        return static::fromBackedEnum($value)
            ?? static::fromAllCases($value);
    }

    /**
     * Validate a mixed value.
     *
     * @psalm-assert string|int $value
     *
     * @phpstan-param string|int $value
     */
    protected static function validateValue(mixed $value): void
    {
        if (! is_string($value) && ! is_int($value)) {
            throw new InvalidArgumentException('Invalid value provided for enum ' . static::class);
        }
    }

    /**
     * Get an enum from all cases by a given value.
     */
    protected static function fromBackedEnum(string|int $value): static|null
    {
        // Need to check BackedEnum first because all Enums are UnitEnum
        if (is_a(static::class, BackedEnum::class, true)) {
            /** @var static $case Get Psalm working and understanding that the static is what gets returned here */
            $case = static::from($value);

            return $case;
        }

        return null;
    }

    /**
     * Get an enum from all cases by a given value.
     */
    protected static function fromAllCases(string|int $value): static
    {
        // Fallback to iterating over all the cases and find a match
        foreach (static::cases() as $case) {
            if ($case->name === $value) {
                /** @var static $case Get Psalm working and understanding that the static is what gets returned here */
                return $case;
            }
        }

        throw new InvalidArgumentException('Invalid value provided for enum ' . static::class);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress ImplementedReturnTypeMismatch The inherited return type 'static' for Valkyrja\Type\Type::asValue is different to the implemented return type for Valkyrja\Type\Types\Enum::asvalue 'Valkyrja\Enum&static'
     */
    public function asValue(): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string|int
    {
        // Need to check BackedEnum first because all Enums are UnitEnum
        if ($this instanceof BackedEnum) {
            return $this->value;
        }

        // Fallback to UnitEnum name property
        /** @var string $name */
        $name = $this->name;

        return $name;
    }

    /**
     * @inheritDoc
     */
    public function modify(callable $closure): static
    {
        throw new RuntimeException('Cannot modify an enum.');
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string|int
    {
        return $this->asFlatValue();
    }
}
