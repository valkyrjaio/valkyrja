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

namespace Valkyrja\Tests\Unit\Auth\Facades;

use Valkyrja\Auth\Auth as Contract;
use Valkyrja\Auth\Facades\Auth as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Auth Facade service.
 *
 * @author Melech Mizrachi
 */
class AuthTest extends FacadeTestCase
{
    /** @inheritDoc */
    protected static string $contract = Contract::class;
    /** @inheritDoc */
    protected static string $facade = Facade::class;

    /**
     * @inheritDoc
     */
    public static function methods(): array
    {
        return [
            ['getConfig'],
            ['getAdapter'],
            ['getRepository'],
            ['getGate'],
            ['getPolicy'],
            ['getFactory'],
            ['requestWithAuthToken'],
            ['requestWithoutAuthToken'],
            ['isAuthenticated'],
            ['getUser'],
            ['setUser'],
            ['getUsers'],
            ['setUsers'],
            ['authenticate'],
            ['authenticateFromSession'],
            ['authenticateFromRequest'],
            ['unAuthenticate'],
            ['setSession'],
            ['unsetSession'],
            ['register'],
            ['forgot'],
            ['reset'],
            ['lock'],
            ['unlock'],
            ['confirmPassword'],
            ['isReAuthenticationRequired'],
        ];
    }
}
