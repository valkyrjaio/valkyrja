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
use Override;
use Stringable;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.2.6
 */
interface ComponentContract extends JsonSerializable, Stringable
{
    /**
     * Create a new component from a string.
     */
    public static function fromValue(string $value): static;

    /**
     * Get the token.
     */
    public function getToken(): string;

    /**
     * Create a new component with the specified token.
     */
    public function withToken(string $token): static;

    /**
     * Get the text.
     */
    public function getText(): string|null;

    /**
     * Create a new component with the specified text.
     */
    public function withText(string|null $text = null): static;

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
