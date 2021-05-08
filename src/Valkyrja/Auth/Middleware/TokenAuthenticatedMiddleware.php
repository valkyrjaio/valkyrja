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

use Valkyrja\Auth\Constants\Header;
use Valkyrja\Auth\Repository;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Http\Request;

/**
 * Class TokenAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class TokenAuthenticatedMiddleware extends AuthenticatedMiddleware
{
    /**
     * Try logging in.
     *
     * @param Repository $repository The auth repository
     * @param Request    $request    The request
     *
     * @throws CryptException
     *
     * @return void
     */
    protected static function tryLogin(Repository $repository, Request $request): void
    {
        // Get the token header value
        $token = $request->getHeaderLine(Header::AUTH_TOKEN);

        // Try to login with the token passed as a header
        $repository->loginWithToken($token);
    }
}
