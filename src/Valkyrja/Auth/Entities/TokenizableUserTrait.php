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

namespace Valkyrja\Auth\Entities;

use Valkyrja\Auth\Constants\SessionId;

/**
 * Trait TokenizableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait TokenizableUserTrait
{
    /**
     *  The token.
     *
     * @var string|null
     */
    protected static ?string $token = null;

    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getTokenSessionId(): string
    {
        return SessionId::USER_TOKEN;
    }

    /**
     * Set the tokenized user.
     *
     * @param string $token The tokenized user
     *
     * @return void
     */
    public static function setTokenized(string $token): void
    {
        static::$token = $token;
    }

    /**
     * Get the user as a token.
     *
     * @return string|null
     */
    public static function asTokenized(): ?string
    {
        return static::$token;
    }

    /**
     * Get user as an array for storing as a token.
     *
     * @return array
     */
    public function asTokenizableArray(): array
    {
        return $this->asStorableArray();
    }

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    abstract public function asStorableArray(): array;
}
