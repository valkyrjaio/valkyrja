<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Middleware\ThrowableCaught;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Factory\ViewResponseFactory;

class ViewThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected ViewResponseFactoryContract $viewResponseFactory = new ViewResponseFactory(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $throwable,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        $statusCode = $response->getStatusCode();

        $response = $this->viewResponseFactory->createResponseFromView(
            template: "$this->errorsTemplateDir/" . ((string) $statusCode->value),
            data: [
                'exception' => $throwable,
                'request'   => $request,
                'response'  => $response,
            ],
            statusCode: $statusCode
        );

        return $handler->throwableCaught($request, $response, $throwable);
    }
}
