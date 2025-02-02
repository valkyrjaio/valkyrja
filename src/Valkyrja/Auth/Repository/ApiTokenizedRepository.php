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

namespace Valkyrja\Auth\Repository;

use Valkyrja\Auth\Entity\Contract\TokenizableUser;
use Valkyrja\Exception\RuntimeException;

use function is_string;

/**
 * Class ApiTokenizedRepository.
 *
 * Use this repository in case an api provides a token you need to use for requests,
 *  but you cannot decrypt it in your application. Note that this will still require a user to
 *  be set via setUser() to work properly.
 *
 * @author Melech Mizrachi
 *
 * @property TokenizableUser $user
 */
class ApiTokenizedRepository extends Repository implements Contract\TokenizedRepository
{
    /**
     * The token.
     *
     * @var string
     */
    protected string $token;

    /**
     * @inheritDoc
     */
    public function authenticateFromSession(): static
    {
        parent::authenticateFromSession();

        $this->user::setTokenized($this->getToken());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSession(): static
    {
        parent::setSession();

        $this->session->set($this->user::getTokenSessionId(), $this->getToken());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getToken(): string
    {
        return $this->token
            ??= $this->getTokenFromSession();
    }

    /**
     * @inheritDoc
     */
    public function authenticateFromToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the token from the session.
     *
     * @return string
     */
    protected function getTokenFromSession(): string
    {
        $token = $this->session->get($this->user::getTokenSessionId());

        if (! is_string($token)) {
            throw new RuntimeException('Invalid session token');
        }

        return $token;
    }
}
