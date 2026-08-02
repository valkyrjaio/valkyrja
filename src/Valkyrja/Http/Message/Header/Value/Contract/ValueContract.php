<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Header\Value\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;
use Override;
use Stringable;
use Valkyrja\Http\Message\Header\Value\Component\Contract\ComponentContract;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 *
 * @extends ArrayAccess<int, ComponentContract|string>
 * @extends Iterator<int, ComponentContract|string>
 */
interface ValueContract extends ArrayAccess, Countable, Iterator, JsonSerializable, Stringable
{
    /**
     * Create a new header value from a string.
     */
    public static function fromValue(string $value): static;

    /**
     * @return array<array-key, ComponentContract|string>
     */
    public function getComponents(): array;

    /**
     * @param ComponentContract|string ...$components The components
     */
    public function withComponents(ComponentContract|string ...$components): static;

    /**
     * @param ComponentContract|string ...$components The components
     */
    public function withAddedComponents(ComponentContract|string ...$components): static;

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
