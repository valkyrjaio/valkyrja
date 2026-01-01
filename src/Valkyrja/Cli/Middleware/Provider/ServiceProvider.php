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

namespace Valkyrja\Cli\Middleware\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            InputReceivedHandlerContract::class     => [self::class, 'publishInputReceivedHandler'],
            ThrowableCaughtHandlerContract::class   => [self::class, 'publishThrowableCaughtHandler'],
            CommandMatchedHandlerContract::class    => [self::class, 'publishCommandMatchedHandler'],
            CommandNotMatchedHandlerContract::class => [self::class, 'publishCommandNotMatchedHandler'],
            CommandDispatchedHandlerContract::class => [self::class, 'publishCommandDispatchedHandler'],
            ExitedHandlerContract::class            => [self::class, 'publishExitedHandler'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            InputReceivedHandlerContract::class,
            CommandDispatchedHandlerContract::class,
            ThrowableCaughtHandlerContract::class,
            CommandMatchedHandlerContract::class,
            CommandNotMatchedHandlerContract::class,
            ExitedHandlerContract::class,
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishInputReceivedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<InputReceivedMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_INPUT_RECEIVED;

        $container->setSingleton(
            InputReceivedHandlerContract::class,
            $handler = new InputReceivedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteDispatchedHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishCommandDispatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandDispatchedMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_DISPATCHED;

        $container->setSingleton(
            CommandDispatchedHandlerContract::class,
            $handler = new CommandDispatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ThrowableCaughtHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishThrowableCaughtHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ThrowableCaughtMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_THROWABLE_CAUGHT;

        $container->setSingleton(
            ThrowableCaughtHandlerContract::class,
            $handler = new ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteMatchedHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishCommandMatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandMatchedMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_MATCHED;

        $container->setSingleton(
            CommandMatchedHandlerContract::class,
            $handler = new CommandMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteNotMatchedHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishCommandNotMatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandNotMatchedMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_NOT_MATCHED;

        $container->setSingleton(
            CommandNotMatchedHandlerContract::class,
            $handler = new CommandNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the TerminatedHandler service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishExitedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ExitedMiddlewareContract>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_EXITED;

        $container->setSingleton(
            ExitedHandlerContract::class,
            $handler = new ExitedHandler($container)
        );

        $handler->add(...$middleware);
    }
}
