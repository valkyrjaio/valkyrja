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

namespace Valkyrja\Auth\Entity\Contract;

interface TokenizableUserContract extends UserContract
{
    /**
     * Get the session id.
     */
    public static function getTokenSessionId(): string;

    /**
     * Set the tokenized user.
     *
     * @param string $token The tokenized user
     */
    public static function setTokenized(string $token): void;

    /**
     * Get the user as a token.
     */
    public static function asTokenized(): string|null;

    /**
     * Get user as an array for storing as a token.
     *
     * @return array<string, mixed>
     */
    public function asTokenizableArray(): array;
}
