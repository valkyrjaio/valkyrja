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
use Valkyrja\Jwt\Contract\Jwt as JwtManager;
use Valkyrja\Jwt\Driver\Contract\Driver as Jwt;
use Valkyrja\Session\Session;

/**
 * Class JwtRepository.
 *
 * @author Melech Mizrachi
 */
class JwtRepository extends TokenizedRepository implements Contract
{
    /**
     * The Jwt.
     *
     * @var Jwt
     */
    protected Jwt $jwt;

    /**
     * JWTRepository constructor.
     *
     * @param Adapter            $adapter The adapter
     * @param JwtManager         $jwt     The JWT service
     * @param Session            $session The session service
     * @param Config|array       $config  The config
     * @param class-string<User> $user    The user class
     */
    public function __construct(Adapter $adapter, JwtManager $jwt, Session $session, Config|array $config, string $user)
    {
        parent::__construct($adapter, $session, $config, $user);

        $this->jwt = $jwt->use();
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
        return $this->usersModel::fromArray($this->jwt->decode($token));
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredFields(): array
    {
        if ($this->config['alwaysAuthenticate']) {
            throw new TokenizationException(
                'alwaysAuthenticate setting is turned on in config. '
                . 'This will result in exposed password and other sensitive user fields in an unsecured JWT. '
                . 'Please use the ' . JwtCryptRepository::class . ' Repository instead.'
            );
        }

        return parent::getRequiredFields();
    }
}
