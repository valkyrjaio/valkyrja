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

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\View\Factory\Contract\ResponseFactory as ViewResponseFactory;
use Valkyrja\View\Factory\ResponseFactory as DefaultViewResponseFactory;

/**
 * Class ViewExceptionMiddleware.
 *
 * @author Melech Mizrachi
 */
class ViewThrowableCaughtMiddleware implements ThrowableCaughtMiddleware
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
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception, ThrowableCaughtHandler $handler): Response
    {
        $statusCode = $response->getStatusCode();

        return $this->viewResponseFactory->createResponseFromView(
            template: "$this->errorsTemplateDir/" . ((string) $statusCode->value),
            data: [
                'exception' => $exception,
                'request'   => $request,
                'response'  => $response,
            ],
            statusCode: $statusCode
        );
    }
}
