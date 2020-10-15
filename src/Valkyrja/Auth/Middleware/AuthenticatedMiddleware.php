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

use Exception;
use Valkyrja\Api\Api;
use Valkyrja\Api\Constants\Status;
use Valkyrja\Auth\Constants\RouteName;
use Valkyrja\Auth\Repository;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Url;

/**
 * Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class AuthenticatedMiddleware extends AuthMiddleware
{
    /**
     * The user to use.
     *
     * @var string|null
     */
    protected static ?string $user = null;

    /**
     * The error message to use.
     *
     * @var string
     */
    protected static string $errorMessage = 'Must be logged in.';

    /**
     * Whether to force a JSON response on failure.
     *
     * @var bool
     */
    protected static bool $forceJson = false;

    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        $auth       = static::getAuth();
        $user       = static::$user ?? $auth->getConfig()['userEntity'];
        $repository = $auth->getRepository($user);

        // Just in case we authenticated already
        if (! $repository->isLoggedIn()) {
            try {
                static::tryLogin($repository, $request);
            } catch (Exception $exception) {
                return static::getFailedAuthenticationResponse($request);
            }
        }

        return $request;
    }

    /**
     * Try logging in.
     *
     * @param Repository $repository The auth repository
     * @param Request    $request    The request
     *
     * @return void
     */
    protected static function tryLogin(Repository $repository, Request $request): void
    {
        // Try to login from the user session
        $repository->loginFromSession();
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
        if (static::$forceJson || $request->isXmlHttpRequest()) {
            /** @var Api $api */
            $api  = static::$container->getSingleton(Api::class);
            $json = $api->jsonFromArray([]);
            $json->setData();
            $json->setMessage(static::$errorMessage);
            $json->setStatusCode(StatusCode::UNAUTHORIZED);
            $json->setStatus(Status::ERROR);

            return self::$responseFactory->createJsonResponse(
                $json->__toArray(),
                StatusCode::UNAUTHORIZED
            );
        }

        /** @var Url $url */
        $url = self::$container->getSingleton(Url::class);

        return self::$responseFactory->createRedirectResponse(
            $url->getUrl((string) static::getConfig('authenticateRoute', RouteName::AUTHENTICATE)),
            StatusCode::UNAUTHORIZED
        );
    }
}
