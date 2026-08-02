<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
     */
    public function handle(ServerRequestContract $request): ResponseContract;

    /**
     * Send the response.
     */
    public function send(ResponseContract $response): static;

    /**
     * Terminate the kernel request.
     *
     * @param ServerRequestContract $request  The request
     * @param ResponseContract      $response The response
     */
    public function terminate(ServerRequestContract $request, ResponseContract $response): void;

    /**
     * Run the kernel.
     *
     * @param ServerRequestContract $request The request
     */
    public function run(ServerRequestContract $request): void;
}
