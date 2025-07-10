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

namespace Valkyrja\Http\Server\Psr;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Psr\Response;
use Valkyrja\Http\Server\Contract\RequestHandler as RequestHandlerContract;

/**
 * Class RequestHandler.
 *
 * @author Melech Mizrachi
 */
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
        $response = $this->requestHandler->handle(RequestFactory::fromPsr($request));

        return new Response($response);
    }
}
