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
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Contract\Api;
use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Contract\Auth;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Url\Contract\Url;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Type\BuiltIn\Support\Obj;

use function is_string;

/**
 * Abstract Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class AuthMiddleware
{
    /**
     * The auth service.
     *
     * @var Auth
     */
    protected static Auth $auth;

    /**
     * The repository.
     *
     * @var Repository
     */
    protected static Repository $repository;

    /**
     * The config.
     *
     * @var Config
     */
    protected static Config $config;

    /**
     * The adapter to use.
     *
     * @var class-string<Adapter>|null
     */
    protected static string|null $adapterName = null;

    /**
     * The user class to use.
     *
     * @var class-string<User>|null
     */
    protected static string|null $userEntity = null;

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
        /** @var Container $container */

        return self::$auth
            ??= $container->getSingleton(Auth::class);
    }

    /**
     * Get the config or a config item.
     *
     * @param string|null $key     [optional]
     * @param mixed|null  $default [optional]
     *
     * @return mixed|null
     */
    protected static function getConfig(string|null $key = null, mixed $default = null): mixed
    {
        $config = self::$config ??= static::getAuth()->getConfig();

        if ($key !== null) {
            return Obj::getValueDotNotation($config, $key, $default);
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
        return self::$repository ??= static::getAuth()->getRepository(static::$userEntity, static::$adapterName);
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
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    protected static function getFailedResponse(ServerRequest $request): Response
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
        /** @var Container $container */
        $responseFactory = $container->getSingleton(ResponseFactory::class);
        $api             = $container->getSingleton(Api::class);
        $json            = $api->jsonFromArray([]);
        $json->setData();
        $json->setMessage(static::$errorMessage);
        $json->setStatusCode(StatusCode::UNAUTHORIZED);
        $json->setStatus(Status::ERROR);

        return $responseFactory->createJsonResponse(
            $json->asArray(),
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
        /** @var Container $container */
        $responseFactory = $container->getSingleton(ResponseFactory::class);
        $authenticateUrl = static::$config->authenticateUrl;

        if (is_string($authenticateUrl) && $authenticateUrl !== '') {
            return $responseFactory->createRedirectResponse(
                $authenticateUrl,
                StatusCode::UNAUTHORIZED
            );
        }

        /** @var Url $url */
        $url = $container->getSingleton(Url::class);

        $redirectRoute = static::$config->authenticateRoute;

        return $responseFactory->createRedirectResponse(
            $url->getUrl($redirectRoute),
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
        /** @var Container $container */
        $logger = $container->getSingleton(Logger::class);
        $logger->exception($exception, $logMessage);
    }
}
