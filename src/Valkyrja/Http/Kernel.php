<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Http\Middleware\MiddlewareAware;

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
     * @param Request $request The request
     *
     * @return Response
     */
    public function handle(Request $request): Response;

    /**
     * Terminate the kernel request.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void;

    /**
     * Run the kernel.
     *
     * @param Request $request The request
     *
     * @return void
     */
    public function run(Request $request = null): void;
}
