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

namespace Valkyrja\Auth\Facades;

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Auth as Contract;
use Valkyrja\Auth\Gate;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Auth.
 *
 * @author Melech Mizrachi
 *
 * @method static array getConfig()
 * @method static Adapter getAdapter(string $name = null)
 * @method static Repository getRepository(string $user = null, string $adapter = null)
 * @method static Gate getGuard(string $name, string $user = null, string $adapter = null)
 * @method static Contract setUser(User $user)
 * @method static User getUser()
 * @method static Contract login(User $user)
 * @method static Contract loginWithToken(string $token)
 * @method static Contract loginFromSession()
 * @method static string getToken()
 * @method static Contract storeToken()
 * @method static bool isLoggedIn()
 * @method static Contract logout()
 * @method static Contract register(User $user)
 * @method static Contract forgot(User $user)
 * @method static Contract reset(string $resetToken, string $password)
 * @method static Contract lock(LockableUser $user)
 * @method static Contract unlock(LockableUser $user)
 * @method static Contract confirmPassword(string $password)
 * @method static Contract storeConfirmedPassword()
 */
class Auth extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
