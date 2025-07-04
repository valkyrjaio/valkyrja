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
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Repository\Contract\JWTCryptRepository as Contract;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Jwt\Contract\Jwt as JwtManager;
use Valkyrja\Session\Contract\Session;

use function is_string;

/**
 * Class JwtCryptRepository.
 *
 * @author Melech Mizrachi
 */
class JwtCryptRepository extends CryptTokenizedRepository implements Contract
{
    /**
     * JWTCryptRepository constructor.
     *
     * @param class-string<User> $user The user class
     */
    public function __construct(
        Adapter $adapter,
        protected JwtManager $jwt,
        Crypt $crypt,
        Session $session,
        Config $config,
        string $user
    ) {
        parent::__construct($adapter, $crypt, $session, $config, $user);
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
        $decodedJwt = $this->jwt->decode($token);

        $decodedToken = $decodedJwt['token'] ?? null;

        if (! is_string($decodedToken)) {
            throw new RuntimeException('Token must be a string');
        }

        return parent::unTokenizeUsers($decodedToken);
    }
}
