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

namespace Valkyrja\Type\String\Contract;

use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @extends TypeContract<string>
 */
interface StringContract extends TypeContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string;

    /**
     * Check if a string starts with a needle.
     */
    public function startsWith(string $needle): bool;

    /**
     * Check if a string starts with any needle.
     */
    public function startsWithAny(string ...$needles): bool;

    /**
     * Check if a string ends with a needle.
     */
    public function endsWith(string $needle): bool;

    /**
     * Check if a string ends with any needle.
     */
    public function endsWithAny(string ...$needles): bool;

    /**
     * Check if a string contains a needle.
     */
    public function contains(string $needle): bool;

    /**
     * Check if a string contains any needle.
     */
    public function containsAny(string ...$needles): bool;

    /**
     * Check if a string's length is longer than a minimum length.
     */
    public function min(int $min = 0): bool;

    /**
     * Check if a string's length is not longer than a maximum length.
     */
    public function max(int $max = 256): bool;

    /**
     * Replace a portion of a string with a replacement.
     */
    public function replace(string $replace, string $replacement): static;

    /**
     * Replace any portion of a string with any replacement.
     *
     * @param string[] $replace
     * @param string[] $replacement
     */
    public function replaceAll(array $replace, array $replacement): static;

    /**
     * Replace all portions of a string with a replacement.
     *
     * @param string[] $replace
     */
    public function replaceAllWith(array $replace, string $replacement): static;

    /**
     * Get a substring from start position with a certain length.
     */
    public function substr(int $start, int|null $length = null): static;

    /**
     * Convert a string to title case.
     */
    public function toTitleCase(): static;

    /**
     * Convert a string to lower case.
     */
    public function toLowerCase(): static;

    /**
     * Convert a string to upper case.
     */
    public function toUpperCase(): static;

    /**
     * Convert a string to capitalized.
     */
    public function toCapitalized(string|null $delimiter = null): static;

    /**
     * Convert a string to capitalized.
     */
    public function toCapitalizedWords(string|null $delimiter = null): static;

    /**
     * Convert a string to snake case.
     */
    public function toSnakeCase(): static;

    /**
     * Convert a string to slug.
     */
    public function toSlug(): static;

    /**
     * Convert a string to studly case.
     */
    public function toStudlyCase(): static;

    /**
     * Convert a string's first character to upper case.
     */
    public function ucFirstLetter(): static;

    /**
     * Check if a string is a valid email.
     */
    public function isEmail(): bool;

    /**
     * Check if a string is alphabetic.
     */
    public function isAlphabetic(): bool;

    /**
     * Check if a string is alphabetic and lowercase.
     */
    public function isLowercase(): bool;

    /**
     * Check if a string is alphabetic and uppercase.
     */
    public function isUppercase(): bool;
}
