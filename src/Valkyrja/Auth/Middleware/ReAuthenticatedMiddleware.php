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

use Valkyrja\Auth\Enums\RouteName;
use Valkyrja\Auth\Enums\SessionId;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Middleware\Middleware;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

use function config;
use function redirect;
use function responseBuilder;
use function router;
use function session;
use function time;

/**
 * Class ReAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ReAuthenticatedMiddleware extends Middleware
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
        if (static::shouldReAuthenticate()) {
            return static::getFailedAuthenticationResponse($request);
        }

        return $request;
    }

    /**
     * Determine if a re-authentication needs to occur.
     *
     * @return bool
     */
    protected static function shouldReAuthenticate(): bool
    {
        $confirmedAt = time() - ((int) session()->get(SessionId::PASSWORD_CONFIRMED_TIMESTAMP, 0));

        return $confirmedAt > config('auth.password_timeout', 10800);
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
            return responseBuilder()->createJsonResponse(
                [],
                StatusCode::LOCKED
            );
        }

        return redirect(
            router()->getUrl((string) config('auth.passwordConfirmRoute', RouteName::PASSWORD_CONFIRM))
        );
    }
}
