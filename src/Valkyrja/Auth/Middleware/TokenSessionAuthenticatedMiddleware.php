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

namespace Valkyrja\Auth\Middleware;

use Valkyrja\Auth\Repository;
use Valkyrja\Http\Request;

/**
 * Class TokenSessionAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class TokenSessionAuthenticatedMiddleware extends AuthenticatedMiddleware
{
    /**
     * Try logging in with a token stored in session.
     *
     * @param Repository $repository The auth repository
     * @param Request    $request    The request
     *
     * @return void
     */
    protected static function tryLogin(Repository $repository, Request $request): void
    {
        // Try to login with the token from session
        $repository->loginFromTokenizedSession();
    }
}
