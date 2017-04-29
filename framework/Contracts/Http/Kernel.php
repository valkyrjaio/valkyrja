<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Routing\Router;

/**
 * Interface Kernel
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Kernel
{
    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Contracts\Application    $application The application
     * @param \Valkyrja\Contracts\Routing\Router $router      The router
     */
    public function __construct(Application $application, Router $router);

    /**
     * Handle a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function handle(Request $request): Response;

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Contracts\Http\Request  $request  The request
     * @param \Valkyrja\Contracts\Http\Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void;

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function run(Request $request = null): void;
}
