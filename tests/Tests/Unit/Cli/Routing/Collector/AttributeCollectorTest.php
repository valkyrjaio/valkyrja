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
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Classes\Cli\Middleware\AllMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Routing\Command\CommandClass;
use Valkyrja\Tests\Classes\Cli\Routing\Command\CommandWithAllAttributesClass;
use Valkyrja\Tests\Classes\Cli\Routing\Command\CommandWithAllMiddlewareClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\CastType;

/**
 * Test the AttributeCollector class.
 */
final class AttributeCollectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testDefaults(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        self::assertEmpty($collector->getRoutes(self::class));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetCommands(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        $commands = $collector->getRoutes(CommandClass::class);

        self::assertNotEmpty($commands);
        self::assertCount(1, $commands);
        self::assertInstanceOf(Route::class, $command = $commands[0]);
        self::assertSame(CommandClass::NAME, $command->getName());
        self::assertSame(CommandClass::DESCRIPTION, $command->getDescription());
        self::assertSame(CommandClass::HELP_TEXT, $command->getHelpTextMessage()->getText());
        self::assertNotEmpty($command->getOptions());
        self::assertInstanceOf(OptionParameter::class, $option = $command->getOptions()[0]);
        self::assertFalse($option->hasCast());
        self::assertNotEmpty($command->getArguments());
        self::assertInstanceOf(ArgumentParameter::class, $argument = $command->getArguments()[0]);
        self::assertFalse($argument->hasCast());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetCommandsWithMoreAttributes(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        $commands = $collector->getRoutes(CommandWithAllAttributesClass::class);

        self::assertNotEmpty($commands);
        self::assertCount(1, $commands);
        self::assertInstanceOf(Route::class, $command = $commands[0]);
        self::assertSame('className.test2.actionName', $command->getName());
        self::assertSame(CommandWithAllAttributesClass::DESCRIPTION, $command->getDescription());
        self::assertSame(CommandWithAllAttributesClass::HELP_TEXT, $command->getHelpTextMessage()->getText());
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
        self::assertSame([RouteDispatchedMiddlewareClass::class], $command->getRouteDispatchedMiddleware());
        self::assertSame([RouteMatchedMiddlewareClass::class], $command->getRouteMatchedMiddleware());
        self::assertSame([ThrowableCaughtMiddlewareClass::class], $command->getThrowableCaughtMiddleware());
        self::assertSame([ExitedMiddlewareClass::class], $command->getExitedMiddleware());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRoutesWithSingleMiddlewareThatHasAllTypes(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );
        $routes = $collector->getRoutes(CommandWithAllMiddlewareClass::class);

        self::assertCount(1, $routes);

        $route = $routes[0];

        self::assertSame(CommandWithAllMiddlewareClass::NAME, $route->getName());
        self::assertSame(CommandWithAllMiddlewareClass::DESCRIPTION, $route->getDescription());
        self::assertSame(CommandWithAllMiddlewareClass::HELP_TEXT, $route->getHelpTextMessage()->getText());
        self::assertSame([AllMiddlewareClass::class], $route->getRouteDispatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getRouteMatchedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getExitedMiddleware());
        self::assertSame([AllMiddlewareClass::class], $route->getThrowableCaughtMiddleware());
    }
}
