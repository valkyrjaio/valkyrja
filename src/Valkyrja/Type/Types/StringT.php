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

namespace Valkyrja\Type\Types;

use Valkyrja\Type\StringT as Contract;
use Valkyrja\Type\Support\Str as Helper;
use Valkyrja\Type\Support\StrCase;

/**
 * Class Str.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class StringT extends Type implements Contract
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static((string) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    public function startsWith(string $needle): bool
    {
        return Helper::startsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    public function startsWithAny(string ...$needles): bool
    {
        return Helper::startsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    public function endsWith(string $needle): bool
    {
        return Helper::endsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    public function endsWithAny(string ...$needles): bool
    {
        return Helper::endsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    public function contains(string $needle): bool
    {
        return Helper::contains($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    public function containsAny(string ...$needles): bool
    {
        return Helper::containsAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    public function min(int $min = 0): bool
    {
        return Helper::min($this->subject, $min);
    }

    /**
     * @inheritDoc
     */
    public function max(int $max = 256): bool
    {
        return Helper::max($this->subject, $max);
    }

    /**
     * @inheritDoc
     */
    public function replace(string $replace, string $replacement): static
    {
        return $this->modify(static fn (string $subject): string => Helper::replace($subject, $replace, $replacement));
    }

    /**
     * @inheritDoc
     */
    public function replaceAll(array $replace, array $replacement): static
    {
        return $this->modify(static fn (string $subject): string => Helper::replaceAll($subject, $replace, $replacement));
    }

    /**
     * @inheritDoc
     */
    public function replaceAllWith(array $replace, string $replacement): static
    {
        return $this->modify(static fn (string $subject): string => Helper::replaceAllWith($subject, $replace, $replacement));
    }

    /**
     * @inheritDoc
     */
    public function substr(int $start, int|null $length = null): static
    {
        return $this->modify(static fn (string $subject): string => Helper::substr($subject, $start, $length));
    }

    /**
     * @inheritDoc
     */
    public function toTitleCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toTitleCase($subject));
    }

    /**
     * @inheritDoc
     */
    public function toLowerCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toLowerCase($subject));
    }

    /**
     * @inheritDoc
     */
    public function toUpperCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toUpperCase($subject));
    }

    /**
     * @inheritDoc
     */
    public function toCapitalized(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toCapitalized($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    public function toCapitalizedWords(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toCapitalizedWords($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    public function toSnakeCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toSnakeCase($subject));
    }

    /**
     * @inheritDoc
     */
    public function toSlug(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toSlug($subject));
    }

    /**
     * @inheritDoc
     */
    public function toStudlyCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toStudlyCase($subject));
    }

    /**
     * @inheritDoc
     */
    public function ucFirstLetter(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::ucFirstLetter($subject));
    }

    /**
     * @inheritDoc
     */
    public function isEmail(): bool
    {
        return Helper::isEmail($this->subject);
    }

    /**
     * @inheritDoc
     */
    public function isAlphabetic(): bool
    {
        return Helper::isAlphabetic($this->subject);
    }

    /**
     * @inheritDoc
     */
    public function isLowercase(): bool
    {
        return Helper::isLowercase($this->subject);
    }

    /**
     * @inheritDoc
     */
    public function isUppercase(): bool
    {
        return Helper::isUppercase($this->subject);
    }
}
