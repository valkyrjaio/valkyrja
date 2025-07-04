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

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\TokenizationException;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Repository\Contract\JWTRepository as Contract;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Jwt\Contract\Jwt as JwtManager;
use Valkyrja\Session\Contract\Session;

use function is_string;

/**
 * Class JwtRepository.
 *
 * @author Melech Mizrachi
 */
class JwtRepository extends TokenizedRepository implements Contract
{
    /**
     * JWTRepository constructor.
     *
     * @param class-string<User> $user The user class
     */
    public function __construct(
        Adapter $adapter,
        protected JwtManager $jwt,
        Session $session,
        Config $config,
        string $user
    ) {
        parent::__construct($adapter, $session, $config, $user);
    }

    /**
     * @inheritDoc
     */
    protected function tokenizeUsers(AuthenticatedUsers $users): string
    {
        return $this->jwt->encode($users->asArray());
    }

    /**
     * @inheritDoc
     */
    protected function unTokenizeUsers(string $token): AuthenticatedUsers
    {
        $decodedToken = $this->jwt->decode($token);

        if (! is_string(array_key_first($decodedToken))) {
            throw new InvalidArgumentException('Provided token is invalid');
        }

        /** @var array<string, mixed> $decodedToken */

        return $this->usersModel::fromArray($decodedToken);
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredFields(): array
    {
        if ($this->config->shouldAlwaysAuthenticate) {
            throw new TokenizationException(
                'alwaysAuthenticate setting is turned on in config. '
                . 'This will result in exposed password and other sensitive user fields in an unsecured JWT. '
                . 'Please use the ' . JwtCryptRepository::class . ' Repository instead.'
            );
        }

        return parent::getRequiredFields();
    }
}
