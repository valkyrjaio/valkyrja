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

namespace Valkyrja\Type\String;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\String\Contract\StringContract;
use Valkyrja\Type\String\Factory\StringCaseFactory;
use Valkyrja\Type\String\Factory\StringFactory;

/**
 * @extends Type<string>
 */
class StringT extends Type implements StringContract
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
        return new static(StringFactory::fromMixed($value));
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
        return StringFactory::startsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function startsWithAny(string ...$needles): bool
    {
        return StringFactory::startsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endsWith(string $needle): bool
    {
        return StringFactory::endsWith($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endsWithAny(string ...$needles): bool
    {
        return StringFactory::endsWithAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function contains(string $needle): bool
    {
        return StringFactory::contains($this->subject, $needle);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function containsAny(string ...$needles): bool
    {
        return StringFactory::containsAny($this->subject, ...$needles);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function min(int $min = 0): bool
    {
        return StringFactory::min($this->subject, $min);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function max(int $max = 256): bool
    {
        return StringFactory::max($this->subject, $max);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replace(string $replace, string $replacement): static
    {
        return $this->modify(static fn (string $subject): string => StringFactory::replace($subject, $replace, $replacement));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replaceAll(array $replace, array $replacement): static
    {
        return $this->modify(
            static fn (string $subject): string => StringFactory::replaceAll($subject, $replace, $replacement)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replaceAllWith(array $replace, string $replacement): static
    {
        return $this->modify(
            static fn (string $subject): string => StringFactory::replaceAllWith($subject, $replace, $replacement)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function substr(int $start, int|null $length = null): static
    {
        return $this->modify(static fn (string $subject): string => StringFactory::substr($subject, $start, $length));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toTitleCase(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toTitleCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toLowerCase(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toLowerCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toUpperCase(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toUpperCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toCapitalized(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toCapitalized($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toCapitalizedWords(string|null $delimiter = null): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toCapitalizedWords($subject, $delimiter));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toSnakeCase(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toSnakeCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toSlug(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toSlug($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toStudlyCase(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::toStudlyCase($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ucFirstLetter(): static
    {
        return $this->modify(static fn (string $subject): string => StringCaseFactory::ucFirstLetter($subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isEmail(): bool
    {
        return StringFactory::isEmail($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isAlphabetic(): bool
    {
        return StringFactory::isAlphabetic($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isLowercase(): bool
    {
        return StringFactory::isLowercase($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isUppercase(): bool
    {
        return StringFactory::isUppercase($this->subject);
    }
}
