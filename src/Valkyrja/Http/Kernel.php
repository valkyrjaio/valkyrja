<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Support\Middleware\MiddlewareAware;

/**
 * Interface Kernel.
 *
 * @author Melech Mizrachi
 */
interface Kernel extends MiddlewareAware
{
    /**
     * Handle a request.
     *
     * @param \Valkyrja\Http\Request $request The request
     *
     * @return \Valkyrja\Http\Response
     */
    public function handle(Request $request): Response;

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Http\Request  $request  The request
     * @param \Valkyrja\Http\Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void;

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Http\Request $request The request
     *
     * @return void
     */
    public function run(Request $request = null): void;
}
