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

namespace Valkyrja\Auth;

/**
 * Interface TokenizableUser.
 *
 * @author Melech Mizrachi
 */
interface TokenizableUser extends User
{
    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getTokenSessionId(): string;

    /**
     * Set the tokenized user.
     *
     * @param string $token The tokenized user
     *
     * @return void
     */
    public static function setTokenized(string $token): void;

    /**
     * Get the user as a token.
     *
     * @return string|null
     */
    public static function asTokenized(): string|null;

    /**
     * Get user as an array for storing as a token.
     *
     * @return array
     */
    public function asTokenizableArray(): array;
}
