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

namespace Valkyrja\Tests\Unit\Cli\Routing\Collection;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Messages;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collection class.
 */
class CollectionTest extends TestCase
{
    public function testDefaults(): void
    {
        $collection = new Collection();

        self::assertEmpty($collection->all());
        self::assertEmpty($collection->getData()->routes);
        self::assertNull($collection->get('test'));
        self::assertFalse($collection->has('test'));
    }

    public function testAddRoute(): void
    {
        $route = new Route(
            name: 'test',
            description: 'description test',
            helpText: new Message(text: 'help'),
            dispatch: new MethodDispatch(self::class, '__construct')
        );

        $collection = new Collection();
        $collection->add($route);

        self::assertSame([$route->getName() => $route], $collection->all());
        self::assertSame([$route->getName() => $route], $collection->getData()->routes);
        self::assertSame($route, $collection->get($route->getName()));
        self::assertTrue($collection->has($route->getName()));
    }

    public function testSetFromData(): void
    {
        $route = new Route(
            name: 'test',
            description: 'description test',
            helpText: new Messages(
                new Message(text: 'help'),
                new NewLine(),
            ),
            dispatch: new MethodDispatch(self::class, '__construct'),
            arguments: [
                new ArgumentParameter(name: 'test', description: 'test'),
            ],
            options: [
                new OptionParameter(name: 'test', description: 'test'),
            ]
        );

        $data = new Data(
            routes: [$route->getName() => $routeClosure = static fn () => $route]
        );

        $collection = new Collection();
        $collection->setFromData($data);

        $routeFromCollection = $collection->get($route->getName());

        self::assertNotEmpty($collection->all());
        self::assertInstanceOf(Route::class, $collection->all()[$route->getName()]);
        self::assertSame([$route->getName() => $routeClosure], $collection->getData()->routes);
        self::assertInstanceOf(Route::class, $routeFromCollection);
        self::assertSame($route->getName(), $routeFromCollection->getName());
        self::assertSame($route->getHelpText()->getText(), $routeFromCollection->getHelpText()->getText());
        self::assertSame($route->getHelpText()->getFormattedText(), $routeFromCollection->getHelpText()->getFormattedText());
        self::assertSame($route->getDescription(), $routeFromCollection->getDescription());
        self::assertNotEmpty($routeFromCollection->getOptions());
        self::assertNotEmpty($routeFromCollection->getArguments());
        self::assertSame($route->getRouteDispatchedMiddleware(), $routeFromCollection->getRouteDispatchedMiddleware());
        self::assertSame($route->getRouteMatchedMiddleware(), $routeFromCollection->getRouteMatchedMiddleware());
        self::assertSame($route->getThrowableCaughtMiddleware(), $routeFromCollection->getThrowableCaughtMiddleware());
        self::assertSame($route->getExitedMiddleware(), $routeFromCollection->getExitedMiddleware());
        self::assertSame($route->getDispatch()->getClass(), $routeFromCollection->getDispatch()->getClass());
        self::assertSame($route->getDispatch()->getMethod(), $routeFromCollection->getDispatch()->getMethod());
        self::assertSame($route->getDispatch()->getArguments(), $routeFromCollection->getDispatch()->getArguments());
        self::assertSame($route->getDispatch()->getDependencies(), $routeFromCollection->getDispatch()->getDependencies());
        self::assertTrue($collection->has($route->getName()));
    }
}
