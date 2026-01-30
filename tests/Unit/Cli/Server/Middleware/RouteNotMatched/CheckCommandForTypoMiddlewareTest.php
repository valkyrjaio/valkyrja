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

namespace Valkyrja\Tests\Unit\Cli\Server\Middleware\RouteNotMatched;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class CheckCommandForTypoMiddlewareTest extends TestCase
{
    public function testRouteNotMatchedDefault(): void
    {
        $input   = new Input(commandName: 'comand');
        $output  = new Output(isInteractive: false);
        $output2 = new Output(isInteractive: false);
        $handler = $this->createMock(RouteNotMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeNotMatched')
            ->withAnyParameters()
            ->willReturnArgument(1);
        $router = $this->createMock(RouterContract::class);
        $router->expects($this->never())
            ->method('dispatch')
            ->withAnyParameters()
            ->willReturn($output2);
        $collection = new Collection();
        $collection->add(
            new Route(
                name: 'command',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            ),
            new Route(
                name: 'command2',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            )
        );

        $middleware = new CheckCommandForTypoMiddleware(
            router: $router,
            collection: $collection,
        );

        ob_start();
        $outputFromMiddleware = $middleware->routeNotMatched($input, $output, $handler);
        $outputFromOutput     = ob_get_clean();

        self::assertSame($output, $outputFromMiddleware);
        self::assertStringContainsString('Did you mean to run one of the following commands?', $outputFromOutput);
        self::assertStringContainsString('(`command` or `command2` or `no`)', $outputFromOutput);
        self::assertStringContainsString('> You answered: `no`', $outputFromOutput);
    }

    public function testRouteNotMatched(): void
    {
        $input   = new Input(commandName: 'comand');
        $output  = new Output(isInteractive: false);
        $output2 = new Output(isInteractive: false);
        $handler = $this->createMock(RouteNotMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeNotMatched')
            ->withAnyParameters()
            ->willReturnArgument(1);
        $router = $this->createMock(RouterContract::class);
        $router->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters()
            ->willReturn($output2);
        $collection = new Collection();
        $collection->add(
            new Route(
                name: 'command',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            ),
            new Route(
                name: 'command2',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            )
        );

        $middleware = new CheckCommandForTypoMiddleware(
            router: $router,
            collection: $collection,
            defaultAnswer: 'command'
        );

        ob_start();
        $outputFromMiddleware = $middleware->routeNotMatched($input, $output, $handler);
        $outputFromOutput     = ob_get_clean();

        self::assertSame($output2, $outputFromMiddleware);
        self::assertStringContainsString('Did you mean to run one of the following commands?', $outputFromOutput);
        self::assertStringContainsString('(`command` or `command2`)', $outputFromOutput);
        self::assertStringContainsString('> You answered: `command`', $outputFromOutput);
    }

    public function testRouteNotMatchedWithInvalidCommandThatIsNotInCollection(): void
    {
        $input   = new Input(commandName: 'comand');
        $output  = new Output(isInteractive: false);
        $output2 = new Output(isInteractive: false);
        $handler = $this->createMock(RouteNotMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeNotMatched')
            ->withAnyParameters()
            ->willReturnArgument(1);
        $router = $this->createMock(RouterContract::class);
        $router->expects($this->never())
            ->method('dispatch')
            ->withAnyParameters()
            ->willReturn($output2);
        $collection = new Collection();
        $collection->add(
            new Route(
                name: 'command',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            ),
            new Route(
                name: 'command2',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            )
        );

        $middleware = new CheckCommandForTypoMiddleware(
            router: $router,
            collection: $collection,
            defaultAnswer: 'invalidcommand'
        );

        ob_start();
        $outputFromMiddleware = $middleware->routeNotMatched($input, $output, $handler);
        $outputFromOutput     = ob_get_clean();

        self::assertSame($output, $outputFromMiddleware);
        self::assertStringContainsString('Did you mean to run one of the following commands?', $outputFromOutput);
        self::assertStringContainsString('(`command` or `command2` or `invalidcommand`)', $outputFromOutput);
        self::assertStringContainsString('> You answered: `invalidcommand`', $outputFromOutput);
    }

    public function testRouteNotMatchedWithNoMatches(): void
    {
        $input   = new Input(commandName: 'comand');
        $output  = new Output(isInteractive: false);
        $output2 = new Output(isInteractive: false);
        $handler = $this->createMock(RouteNotMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeNotMatched')
            ->withAnyParameters()
            ->willReturnArgument(1);
        $router = $this->createMock(RouterContract::class);
        $router->expects($this->never())
            ->method('dispatch')
            ->withAnyParameters()
            ->willReturn($output2);
        $collection = new Collection();
        $collection->add(
            new Route(
                name: 'nomatch',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            ),
            new Route(
                name: 'nomatch2',
                description: 'description',
                helpText: new Message('help text'),
                dispatch: new MethodDispatch(class: self::class, method: '__construct')
            )
        );

        $middleware = new CheckCommandForTypoMiddleware(
            router: $router,
            collection: $collection
        );

        ob_start();
        $outputFromMiddleware = $middleware->routeNotMatched($input, $output, $handler);
        $outputFromOutput     = ob_get_clean();

        self::assertSame($output, $outputFromMiddleware);
        self::assertEmpty($outputFromOutput);
    }
}
