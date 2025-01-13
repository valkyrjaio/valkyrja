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

namespace Valkyrja\Http\Message\Header\Value\Component\Contract;

use JsonSerializable;
use Stringable;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.2.6
 */
interface Component extends JsonSerializable, Stringable
{
    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $token
     *
     * @return static
     */
    public function withToken(string $token): static;

    /**
     * @return string|null
     */
    public function getText(): ?string;

    /**
     * @param string|null $text
     *
     * @return static
     */
    public function withText(?string $text = null): static;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
