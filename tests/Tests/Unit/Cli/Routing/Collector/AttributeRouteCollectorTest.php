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

namespace Valkyrja\Tests\Unit\Cli\Routing\Collector;

use ReflectionException;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Cli\Routing\Collector\AttributeRouteCollector;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Fixtures\Cli\Middleware\AllMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Routing\Command\CommandFixture;
use Valkyrja\Tests\Fixtures\Cli\Routing\Command\CommandWithAllAttributesFixture;
use Valkyrja\Tests\Fixtures\Cli\Routing\Command\CommandWithAllMiddlewareFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\CastType;

/**
 * Test the AttributeCollector class.
 */
final class AttributeRouteCollectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testDefaults(): void
    {
        $collector = new AttributeRouteCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        self::assertEmpty($collector->getRoutes(self::class));
    }

    public function testGetCommands(): void
    {
        $collector = new AttributeRouteCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        $commands = $collector->getRoutes(CommandFixture::class);

        self::assertNotEmpty($commands);
        self::assertCount(1, $commands);
        self::assertInstanceOf(Route::class, $command = $commands[0]);
        self::assertSame(CommandFixture::NAME, $command->getName());
        self::assertSame(CommandFixture::DESCRIPTION, $command->getDescription());
        self::assertSame(CommandFixture::HELP_TEXT, $command->getHelpTextMessage()->getText());
        self::assertSame([CommandFixture::class, 'handler'], $command->getHandler());
        self::assertNotEmpty($command->getOptions());
        self::assertInstanceOf(OptionParameter::class, $option = $command->getOptions()[0]);
        self::assertFalse($option->hasCast());
        self::assertNotEmpty($command->getArguments());
        self::assertInstanceOf(ArgumentParameter::class, $argument = $command->getArguments()[0]);
        self::assertFalse($argument->hasCast());
    }

    public function testGetCommandsWithMoreAttributes(): void
    {
        $collector = new AttributeRouteCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        $commands = $collector->getRoutes(CommandWithAllAttributesFixture::class);

        self::assertNotEmpty($commands);
        self::assertCount(1, $commands);
        self::assertInstanceOf(Route::class, $command = $commands[0]);
        self::assertSame('className.test2.actionName', $command->getName());
        self::assertSame(CommandWithAllAttributesFixture::DESCRIPTION, $command->getDescription());
        self::assertSame(CommandWithAllAttributesFixture::HELP_TEXT, $command->getHelpTextMessage()->getText());
        self::assertSame([CommandWithAllAttributesFixture::class, 'handler'], $command->getHandler());
        self::assertNotEmpty($command->getOptions());
        self::assertInstanceOf(OptionParameter::class, $option = $command->getOptions()[0]);
        self::assertNotEmpty($command->getArguments());
        self::assertSame('optionName', $option->getName());
        self::assertSame('The option for the command', $option->getDescription());
        self::assertSame(CastType::string->value, $option->getCast()->type);
        self::assertSame('name', $option->getValueDisplayName());
        self::assertSame('foo', $option->getDefaultValue());
        self::assertSame(['o'], $option->getShortNames());
        self::assertSame(['foo', 'bar'], $option->getValidValues());
        self::assertSame(OptionMode::REQUIRED, $option->getMode());
        self::assertSame(OptionValueMode::ARRAY, $option->getValueMode());
        self::assertInstanceOf(ArgumentParameter::class, $argument = $command->getArguments()[0]);
        self::assertSame('argumentName', $argument->getName());
        self::assertSame('The argument for the command', $argument->getDescription());
        self::assertSame(ArgumentMode::REQUIRED, $argument->getMode());
        self::assertSame(ArgumentValueMode::ARRAY, $argument->getValueMode());
        self::assertSame(CastType::string->value, $argument->getCast()->type);
        self::assertSame([RouteDispatchedMiddlewareFixture::class], $command->getRouteDispatchedMiddleware());
        self::assertSame([RouteMatchedMiddlewareFixture::class], $command->getRouteMatchedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareFixture::class], $command->getThrowableCaughtMiddleware());
        self::assertSame([ProcessExitingMiddlewareFixture::class], $command->getProcessExitingMiddleware());
    }

    public function testGetRoutesWithSingleMiddlewareThatHasAllTypes(): void
    {
        $collector = new AttributeRouteCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );
        $routes = $collector->getRoutes(CommandWithAllMiddlewareFixture::class);

        self::assertCount(1, $routes);

        $route = $routes[0];

        self::assertSame(CommandWithAllMiddlewareFixture::NAME, $route->getName());
        self::assertSame(CommandWithAllMiddlewareFixture::DESCRIPTION, $route->getDescription());
        self::assertSame(CommandWithAllMiddlewareFixture::HELP_TEXT, $route->getHelpTextMessage()->getText());
        self::assertSame([AllMiddlewareFixture::class], $route->getRouteDispatchedMiddleware());
        self::assertSame([AllMiddlewareFixture::class], $route->getRouteMatchedMiddleware());
        self::assertSame([AllMiddlewareFixture::class], $route->getProcessExitingMiddleware());
        self::assertSame([AllMiddlewareFixture::class], $route->getThrowableCaughtMiddleware());
    }
}
