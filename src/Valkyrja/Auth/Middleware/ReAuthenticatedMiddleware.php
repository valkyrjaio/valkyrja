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

use Valkyrja\Auth\Constants\RouteName;
use Valkyrja\Auth\Constants\SessionId;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Url;

use function time;

/**
 * Class ReAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ReAuthenticatedMiddleware extends AuthMiddleware
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
        $confirmedAt = time() - ((int) static::getSession()->get(SessionId::PASSWORD_CONFIRMED_TIMESTAMP, 0));

        return $confirmedAt > static::getConfig('password_timeout', 10800);
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
            return static::getResponseFactory()->createJsonResponse([], StatusCode::LOCKED);
        }

        /** @var Url $url */
        $url = self::$container->getSingleton(Url::class);

        return static::getResponseFactory()->createRedirectResponse(
            $url->getUrl((string) static::getConfig('passwordConfirmRoute', RouteName::PASSWORD_CONFIRM))
        );
    }
}
