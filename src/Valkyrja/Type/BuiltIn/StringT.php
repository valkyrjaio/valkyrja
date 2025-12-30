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

namespace Valkyrja\Type\BuiltIn;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\BuiltIn\Contract\StringT as Contract;
use Valkyrja\Type\BuiltIn\Support\Str as Helper;
use Valkyrja\Type\BuiltIn\Support\StrCase;

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
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static(Helper::fromMixed($value));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function startsWith(string $needle): bool
    {
        return Helper::startsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function startsWithAny(string ...$needles): bool
    {
        return Helper::startsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endsWith(string $needle): bool
    {
        return Helper::endsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endsWithAny(string ...$needles): bool
    {
        return Helper::endsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function contains(string $needle): bool
    {
        return Helper::contains($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function containsAny(string ...$needles): bool
    {
        return Helper::containsAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function min(int $min = 0): bool
    {
        return Helper::min($this->subject, $min);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function max(int $max = 256): bool
    {
        return Helper::max($this->subject, $max);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replace(string $replace, string $replacement): static
    {
        return $this->modify(static fn (string $subject): string => Helper::replace($subject, $replace, $replacement));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replaceAll(array $replace, array $replacement): static
    {
        return $this->modify(
            static fn (string $subject): string => Helper::replaceAll($subject, $replace, $replacement)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replaceAllWith(array $replace, string $replacement): static
    {
        return $this->modify(
            static fn (string $subject): string => Helper::replaceAllWith($subject, $replace, $replacement)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function substr(int $start, int|null $length = null): static
    {
        return $this->modify(static fn (string $subject): string => Helper::substr($subject, $start, $length));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toTitleCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toTitleCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toLowerCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toLowerCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toUpperCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toUpperCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toCapitalized(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toCapitalized($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toCapitalizedWords(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toCapitalizedWords($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toSnakeCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toSnakeCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toSlug(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toSlug($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toStudlyCase(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::toStudlyCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ucFirstLetter(): static
    {
        return $this->modify(static fn (string $subject): string => StrCase::ucFirstLetter($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isEmail(): bool
    {
        return Helper::isEmail($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isAlphabetic(): bool
    {
        return Helper::isAlphabetic($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isLowercase(): bool
    {
        return Helper::isLowercase($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isUppercase(): bool
    {
        return Helper::isUppercase($this->subject);
    }
}
