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

use Valkyrja\Auth\Auth;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Support\Middleware;
use Valkyrja\Session\Session;

/**
 * Abstract Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class AuthMiddleware extends Middleware
{
    /**
     * Get auth.
     *
     * @return Auth
     */
    protected static function getAuth(): Auth
    {
        return self::$container->getSingleton(Auth::class);
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
     * Get the response factory.
     *
     * @return ResponseFactory
     */
    protected static function getResponseFactory(): ResponseFactory
    {
        return self::$container->getSingleton(ResponseFactory::class);
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
}
