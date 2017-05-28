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

/**
 * Interface Kernel.
 *
 * @author Melech Mizrachi
 */
interface Kernel
{
    /**
     * Handle a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
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
     */
    public function run(Request $request = null): void;
}
