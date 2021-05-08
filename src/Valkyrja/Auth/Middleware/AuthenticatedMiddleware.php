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
use Valkyrja\Auth\Repository;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Class AuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class AuthenticatedMiddleware extends AuthMiddleware
{
    /**
     * The error message to use.
     *
     * @var string
     */
    protected static string $errorMessage = 'Must be logged in.';

    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        $repository = static::getRepository();

        // Just in case we authenticated already
        if (! $repository->isLoggedIn()) {
            try {
                static::tryLogin($repository, $request);
            } catch (Exception $exception) {
                return static::getFailedResponse($request);
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
}
