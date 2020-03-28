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
use Valkyrja\Auth\Enums\ConfigValue;
use Valkyrja\Auth\Enums\RouteName;
use Valkyrja\Http\Middleware\Middleware;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

use function auth;
use function json;

/**
 * Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class AuthenticatedMiddleware extends Middleware
{
    /**
     * The user to use.
     *
     * @var string
     */
    protected static string $user = ConfigValue::USER;

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
                // Try to login with the session
                $repository->loginFromSession();
            } catch (Exception $exception) {
                return static::getFailedAuthenticationResponse($request);
            }
        }

        return $request;
    }

    /**
     * Get the failed authentication response.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    protected static function getFailedAuthenticationResponse(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return json();
        }

        return redirect(
            router()->getUrl((string) config('auth.authenticateRoute', RouteName::AUTHENTICATE))
        );
    }
}
