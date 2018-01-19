<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support\Middleware;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Abstract Class BeforeMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class Middleware
{
    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return \Valkyrja\Http\Request
     */
    abstract public static function before(Request $request): Request;

    /**
     * Middleware handler for after a request is dispatched.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return \Valkyrja\Http\Response
     */
    abstract public static function after(Request $request, Response $response): Response;

    /**
     * Middleware handler run when the application is terminating.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    abstract public static function terminate(Request $request, Response $response): void;
}
