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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface RequestHandler.
 *
 * @author Melech Mizrachi
 */
interface RequestHandler
{
    /**
     * Handle a request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function handle(ServerRequest $request): Response;

    /**
     * Send the response.
     *
     * @param Response $response
     *
     * @return static
     */
    public function send(Response $response): static;

    /**
     * Terminate the kernel request.
     *
     * @param ServerRequest $request  The request
     * @param Response      $response The response
     *
     * @return void
     */
    public function terminate(ServerRequest $request, Response $response): void;

    /**
     * Run the kernel.
     *
     * @param ServerRequest $request The request
     *
     * @return void
     */
    public function run(ServerRequest $request): void;
}
