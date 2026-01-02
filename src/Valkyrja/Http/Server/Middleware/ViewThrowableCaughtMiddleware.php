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

namespace Valkyrja\Http\Server\Middleware;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract as ViewResponseFactory;
use Valkyrja\View\Factory\ResponseFactory as DefaultViewResponseFactory;

class ViewThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected ViewResponseFactory $viewResponseFactory = new DefaultViewResponseFactory(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $exception,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        $statusCode = $response->getStatusCode();

        $response = $this->viewResponseFactory->createResponseFromView(
            template: "$this->errorsTemplateDir/" . ((string) $statusCode->value),
            data: [
                'exception' => $exception,
                'request'   => $request,
                'response'  => $response,
            ],
            statusCode: $statusCode
        );

        return $handler->throwableCaught($request, $response, $exception);
    }
}
