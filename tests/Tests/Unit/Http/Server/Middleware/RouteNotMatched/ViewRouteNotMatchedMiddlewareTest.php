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

namespace Valkyrja\Tests\Unit\Http\Server\Middleware\RouteNotMatched;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Renderer\Contract\RendererContract;

/**
 * Class ViewRouteNotMatchedMiddlewareTest.
 */
final class ViewRouteNotMatchedMiddlewareTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRouteNotMatched(): void
    {
        $statusCode = StatusCode::NOT_FOUND;
        $request    = new ServerRequest();
        $response   = new Response(statusCode: $statusCode);
        $handler    = new RouteNotMatchedHandler();

        $args = [
            'request'  => $request,
            'response' => $response,
        ];

        $templateText = 'Error: 404';

        $view = $this->createMock(RendererContract::class);
        $view->expects($this->once())
            ->method('render')
            ->with('errors/404', $args)
            ->willReturn($templateText);

        $middleware = new ViewRouteNotMatchedMiddleware(renderer: $view);

        $response = $middleware->routeNotMatched($request, $response, $handler);

        self::assertSame($templateText, (string) $response->getBody());
        self::assertSame($statusCode, $response->getStatusCode());
    }
}
