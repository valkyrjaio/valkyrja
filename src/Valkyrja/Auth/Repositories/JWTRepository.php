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

namespace Valkyrja\Auth\Repositories;

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\AuthenticatedUsers;
use Valkyrja\Auth\Config\Config;
use Valkyrja\Auth\Exceptions\TokenizationException;
use Valkyrja\Auth\JWTRepository as Contract;
use Valkyrja\JWT\Driver as JWT;
use Valkyrja\JWT\JWT as JWTManager;
use Valkyrja\Session\Session;

/**
 * Class JWTRepository.
 *
 * @author Melech Mizrachi
 */
class JWTRepository extends TokenizedRepository implements Contract
{
    /**
     * The JWT.
     *
     * @var JWT
     */
    protected JWT $jwt;

    /**
     * JWTRepository constructor.
     *
     * @param Adapter      $adapter The adapter
     * @param JWTManager   $jwt     The JWT service
     * @param Session      $session The session service
     * @param Config|array $config  The config
     * @param string       $user    The user class
     */
    public function __construct(Adapter $adapter, JWTManager $jwt, Session $session, Config|array $config, string $user)
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
                . 'Please use the ' . JWTCryptRepository::class . ' Repository instead.'
            );
        }

        return parent::getRequiredFields();
    }
}
