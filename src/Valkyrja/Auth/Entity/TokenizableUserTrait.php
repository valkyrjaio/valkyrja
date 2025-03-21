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

namespace Valkyrja\Auth\Entity;

use JsonException;
use Valkyrja\Auth\Constant\SessionId;

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
     * @inheritDoc
     */
    public static function getTokenSessionId(): string
    {
        return SessionId::USER_TOKEN;
    }

    /**
     * @inheritDoc
     */
    public static function setTokenized(string $token): void
    {
        static::$token = $token;
    }

    /**
     * @inheritDoc
     */
    public static function asTokenized(): ?string
    {
        return static::$token;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     *
     * @return array<string, mixed>
     */
    public function asTokenizableArray(): array
    {
        /** @var string $idField */
        $idField = static::getIdField();

        return [
            $idField => $this->{$idField},
        ];
    }

    /**
     * @inheritDoc
     */
    abstract public function asStorableArray(): array;
}
