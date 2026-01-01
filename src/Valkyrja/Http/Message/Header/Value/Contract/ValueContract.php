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
use Override;
use Stringable;
use Valkyrja\Http\Message\Header\Value\Component\Contract\ComponentContract;

/**
 * Class ValueContract.
 *
 * @author Melech Mizrachi
 *
 * @see    https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 *
 * @extends ArrayAccess<int, ComponentContract>
 * @extends Iterator<int, ComponentContract>
 */
interface ValueContract extends ArrayAccess, Countable, Iterator, JsonSerializable, Stringable
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
     * @return ComponentContract[]
     */
    public function getComponents(): array;

    /**
     * @param ComponentContract|string ...$components The components
     *
     * @return static
     */
    public function withComponents(ComponentContract|string ...$components): static;

    /**
     * @param ComponentContract|string ...$components The components
     *
     * @return static
     */
    public function withAddedComponents(ComponentContract|string ...$components): static;

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
