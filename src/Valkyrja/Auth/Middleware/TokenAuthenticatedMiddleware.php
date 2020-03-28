<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Middleware;

use Exception;
use Valkyrja\Auth\Enums\Header;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

use function auth;
use function request;

/**
 * Class TokenAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class TokenAuthenticatedMiddleware extends AuthenticatedMiddleware
{
    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        $repository = auth()->getRepository(static::$user);

        // Just in case we authenticated already
        if (! $repository->isLoggedIn()) {
            try {
                $token = request()->getHeaderLine(Header::AUTH_TOKEN);
                // Try to login with the token
                $repository->loginWithToken($token);
            } catch (Exception $exception) {
                return static::getFailedAuthenticationResponse($request);
            }
        }

        return $request;
    }
}
