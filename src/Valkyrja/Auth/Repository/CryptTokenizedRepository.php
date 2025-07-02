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
use Valkyrja\Auth\Repository\Contract\CryptTokenizedRepository as Contract;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\Exception\CryptException;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Session\Contract\Session;

use function is_string;

/**
 * Class CryptTokenizedRepository.
 *
 * @author Melech Mizrachi
 */
class CryptTokenizedRepository extends TokenizedRepository implements Contract
{
    /**
     * CryptTokenizedRepository constructor.
     *
     * @param class-string<User> $user The user class
     */
    public function __construct(
        Adapter $adapter,
        protected Crypt $crypt,
        Session $session,
        Config $config,
        string $user
    ) {
        parent::__construct($adapter, $session, $config, $user);
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    protected function tokenizeUsers(AuthenticatedUsers $users): string
    {
        return $this->crypt->encryptArray($users->asArray());
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    protected function unTokenizeUsers(string $token): AuthenticatedUsers
    {
        $decodedToken = $this->crypt->decryptArray($token);

        if (! is_string(array_key_first($decodedToken))) {
            throw new InvalidArgumentException('Provided token is invalid');
        }

        /** @var array<string, mixed> $decodedToken */

        return $this->usersModel::fromArray($decodedToken);
    }
}
