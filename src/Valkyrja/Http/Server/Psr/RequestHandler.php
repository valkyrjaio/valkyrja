<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Psr;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Valkyrja\Http\Message\Request\Factory\PsrRequestFactory;
use Valkyrja\Http\Message\Response\Psr\Response;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        protected RequestHandlerContract $requestHandler
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->requestHandler->handle(PsrRequestFactory::fromPsr($request));

        return new Response($response);
    }
}
