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
use Valkyrja\Auth\JWTCryptRepository as Contract;
use Valkyrja\Crypt\Crypt;
use Valkyrja\JWT\Driver as JWT;
use Valkyrja\JWT\JWT as JWTManager;
use Valkyrja\Session\Session;

/**
 * Class JWTCryptRepository.
 *
 * @author Melech Mizrachi
 */
class JWTCryptRepository extends CryptTokenizedRepository implements Contract
{
    /**
     * The JWT service.
     *
     * @var JWT
     */
    protected JWT $jwt;

    /**
     * JWTCryptRepository constructor.
     *
     * @param Adapter    $adapter The adapter
     * @param JWTManager $jwt     The JWT service
     * @param Crypt      $crypt   The crypt service
     * @param Session    $session The session service
     * @param array      $config  The config
     * @param string     $user    The user class
     */
    public function __construct(Adapter $adapter, JWTManager $jwt, Crypt $crypt, Session $session, array $config, string $user)
    {
        parent::__construct($adapter, $crypt, $session, $config, $user);

        $this->jwt = $jwt->use();
    }

    /**
     * @inheritDoc
     */
    protected function tokenizeUsers(AuthenticatedUsers $users): string
    {
        return $this->jwt->encode(['token' => parent::tokenizeUsers($users)]);
    }

    /**
     * @inheritDoc
     */
    protected function unTokenizeUsers(string $token): AuthenticatedUsers
    {
        return parent::unTokenizeUsers($this->jwt->decode($token)['token']);
    }
}
