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

namespace Valkyrja\Http\Middleware;

use Valkyrja\Http\Middleware as MiddlewareContract;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Abstract Class Middleware.
 *
 * @author Melech Mizrachi
 */
abstract class Middleware implements MiddlewareContract
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
        // Do logic using the request before it is processed by the controller action, here

        return $request;
    }

    /**
     * Middleware handler for after a request is dispatched.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return Response
     */
    public static function after(Request $request, Response $response): Response
    {
        // Modify the response after its been processed by the controller action, here

        return $response;
    }

    /**
     * Middleware handler run when the application is terminating.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    public static function terminate(Request $request, Response $response): void
    {
        // Do stuff after termination (after the response has been sent) here
    }
}
