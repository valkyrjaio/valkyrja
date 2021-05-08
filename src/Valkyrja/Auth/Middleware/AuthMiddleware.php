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
use Valkyrja\Auth\Auth;
use Valkyrja\Auth\Constants\RouteName;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Log\Facades\Logger;
use Valkyrja\Routing\Support\Middleware;
use Valkyrja\Routing\Url;
use Valkyrja\Session\Session;

/**
 * Abstract Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class AuthMiddleware extends Middleware
{
    /**
     * The auth service.
     *
     * @var Auth
     */
    protected static Auth $auth;

    /**
     * The user class to use.
     *
     * @var string
     */
    protected static string $userEntity;

    /**
     * The error message to use.
     *
     * @var string
     */
    protected static string $errorMessage = 'Unauthorized';

    /**
     * Whether to force a JSON response on failure.
     *
     * @var bool
     */
    protected static bool $forceJson = false;

    /**
     * Get auth.
     *
     * @return Auth
     */
    protected static function getAuth(): Auth
    {
        return self::$auth ?? self::$auth = self::$container->getSingleton(Auth::class);
    }

    /**
     * Get the session manager.
     *
     * @return Session
     */
    protected static function getSession(): Session
    {
        return self::$container->getSingleton(Session::class);
    }

    /**
     * Get the config or a config item.
     *
     * @param string|null $key     [optional]
     * @param mixed|null  $default [optional]
     *
     * @return mixed|null
     */
    protected static function getConfig(string $key = null, $default = null)
    {
        $config = static::getAuth()->getConfig();

        if (null !== $key) {
            return $config[$key] ?? $default;
        }

        return $config;
    }

    /**
     * Get the auth repository for the user entity specified (or default to the application default).
     *
     * @return Repository
     */
    protected static function getRepository(): Repository
    {
        $auth       = static::getAuth();
        $userEntity = static::$userEntity ?? $auth->getConfig()['userEntity'];

        return $auth->getRepository($userEntity);
    }

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    protected static function getUser(): User
    {
        return static::getRepository()->getUser();
    }

    /**
     * Get the failed response.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    protected static function getFailedResponse(Request $request): Response
    {
        if (static::$forceJson || $request->isXmlHttpRequest()) {
            return static::getFailedJsonResponse();
        }

        return static::getFailedRegularResponse();
    }

    /**
     * Get the failed JSON response.
     *
     * @return JsonResponse
     */
    protected static function getFailedJsonResponse(): JsonResponse
    {
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

    /**
     * Get the failed non-JSON response.
     *
     * @return Response
     */
    protected static function getFailedRegularResponse(): Response
    {
        /** @var Url $url */
        $url = self::$container->getSingleton(Url::class);

        return self::$responseFactory->createRedirectResponse(
            $url->getUrl((string) static::getConfig('authenticateRoute', RouteName::AUTHENTICATE)),
            StatusCode::UNAUTHORIZED
        );
    }

    /**
     * Handle an exception.
     *
     * @param Exception $exception  The exception
     * @param string    $logMessage [optional] The log message
     *
     * @return void
     */
    protected static function handleException(Exception $exception, string $logMessage = ''): void
    {
        Logger::exception($exception, $logMessage);
    }
}
