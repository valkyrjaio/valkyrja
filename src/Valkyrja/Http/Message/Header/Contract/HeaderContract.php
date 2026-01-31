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

namespace Valkyrja\Http\Message\Header\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;
use Override;
use Stringable;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 *
 * @extends ArrayAccess<int, ValueContract|string>
 * @extends Iterator<int, ValueContract|string>
 */
interface HeaderContract extends ArrayAccess, Countable, Iterator, JsonSerializable, Stringable
{
    /**
     * Create a new Header from a string representation.
     */
    public static function fromValue(string $value): static;

    /**
     * Get the header name.
     */
    public function getName(): string;

    /**
     * Get the normalized header name.
     *
     * @return lowercase-string
     */
    public function getNormalizedName(): string;

    /**
     * Create a new Header with the specified name.
     */
    public function withName(string $name): static;

    /**
     * @return array<array-key, ValueContract|string>
     */
    public function getValues(): array;

    /**
     * @param ValueContract|string ...$values The values
     */
    public function withValues(ValueContract|string ...$values): static;

    /**
     * @param ValueContract|string ...$values The values
     */
    public function withAddedValues(ValueContract|string ...$values): static;

    /**
     * Get the values as a string.
     */
    public function getValuesAsString(): string;

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string;

    /**
     * @inheritDoc
     */
    public function __toString(): string;
}
