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

namespace Valkyrja\Http\Server\Handler\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

interface RequestHandlerContract
{
    /**
     * Handle a request.
     *
     * @param ServerRequestContract $request The request
     *
     * @return ResponseContract
     */
    public function handle(ServerRequestContract $request): ResponseContract;

    /**
     * Send the response.
     *
     * @param ResponseContract $response
     *
     * @return static
     */
    public function send(ResponseContract $response): static;

    /**
     * Terminate the kernel request.
     *
     * @param ServerRequestContract $request  The request
     * @param ResponseContract      $response The response
     *
     * @return void
     */
    public function terminate(ServerRequestContract $request, ResponseContract $response): void;

    /**
     * Run the kernel.
     *
     * @param ServerRequestContract $request The request
     *
     * @return void
     */
    public function run(ServerRequestContract $request): void;
}
