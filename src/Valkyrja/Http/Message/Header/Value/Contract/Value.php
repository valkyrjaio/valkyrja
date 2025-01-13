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

namespace Valkyrja\Http\Message\Header\Value\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;
use Stringable;
use Valkyrja\Http\Message\Header\Value\Component\Contract\Component;

/**
 * Class Value.
 *
 * @author Melech Mizrachi
 *
 * @see    https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 *
 * @extends ArrayAccess<int, Component>
 * @extends Iterator<int, Component>
 */
interface Value extends ArrayAccess, Countable, Iterator, JsonSerializable, Stringable
{
    /**
     * Create a new header value from a string.
     *
     * @param string $value
     *
     * @return static
     */
    public static function fromValue(string $value): static;

    /**
     * @return Component[]
     */
    public function getComponents(): array;

    /**
     * @param array<int, Component|string> $components
     *
     * @return static
     */
    public function withComponents(Component|string ...$components): static;

    /**
     * @param array<int, Component|string> $components
     *
     * @return static
     */
    public function withAddedComponents(Component|string ...$components): static;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
