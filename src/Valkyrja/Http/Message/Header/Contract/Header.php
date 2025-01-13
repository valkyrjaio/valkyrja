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
use Stringable;
use Valkyrja\Http\Message\Header\Value\Contract\Value;

/**
 * Class Header.
 *
 * @author Melech Mizrachi
 *
 * @see    https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 *
 * @extends ArrayAccess<int, Value>
 * @extends Iterator<int, Value>
 */
interface Header extends ArrayAccess, Countable, Iterator, JsonSerializable, Stringable
{
    /**
     * Create a new Header from a string representation.
     *
     * @param string $value
     *
     * @return static
     */
    public static function fromValue(string $value): static;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getNormalizedName(): string;

    /**
     * @param string $name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * @return Value[]
     */
    public function getValues(): array;

    /**
     * @param array<int, Value|string> $values
     *
     * @return static
     */
    public function withValues(Value|string ...$values): static;

    /**
     * @param array<int, Value|string> $values
     *
     * @return static
     */
    public function withAddedValues(Value|string ...$values): static;

    /**
     * @return string
     */
    public function getValuesAsString(): string;

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
