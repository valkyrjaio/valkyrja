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

namespace Valkyrja\Auth\Facade;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Contract\Auth as Contract;
use Valkyrja\Auth\Entity\Contract\LockableUser;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;

/**
 * Class Auth.
 *
 * @author Melech Mizrachi
 *
 * @method static array              getConfig()
 * @method static Adapter            getAdapter(string $name = null)
 * @method static Repository         getRepository(string $user = null, string $adapter = null)
 * @method static Gate               getGate(string $name, string $user = null, string $adapter = null)
 * @method static bool               isAuthenticated()
 * @method static Contract           setUser(User $user)
 * @method static User               getUser()
 * @method static Contract           setUsers(AuthenticatedUsers $users)
 * @method static AuthenticatedUsers getUsers()
 * @method static Contract           authenticate(User $user)
 * @method static Contract           authenticateFromSession()
 * @method static Contract           authenticateFromRequest(ServerRequest $request)
 * @method static Contract           unAuthenticate()
 * @method static Contract           setSession()
 * @method static Contract           unsetSession()
 * @method static Contract           register(User $user)
 * @method static Contract           forgot(User $user)
 * @method static Contract           reset(string $resetToken, string $password)
 * @method static Contract           lock(LockableUser $user)
 * @method static Contract           unlock(LockableUser $user)
 * @method static Contract           confirmPassword(string $password)
 * @method static bool               isReAuthenticationRequired()
 */
class Auth extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
