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
use Valkyrja\Application\Env;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Middleware\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            InputReceivedHandler::class     => [self::class, 'publishInputReceivedHandler'],
            ThrowableCaughtHandler::class   => [self::class, 'publishThrowableCaughtHandler'],
            CommandMatchedHandler::class    => [self::class, 'publishCommandMatchedHandler'],
            CommandNotMatchedHandler::class => [self::class, 'publishCommandNotMatchedHandler'],
            CommandDispatchedHandler::class => [self::class, 'publishCommandDispatchedHandler'],
            ExitedHandler::class            => [self::class, 'publishExitedHandler'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            InputReceivedHandler::class,
            CommandDispatchedHandler::class,
            ThrowableCaughtHandler::class,
            CommandMatchedHandler::class,
            CommandNotMatchedHandler::class,
            ExitedHandler::class,
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishInputReceivedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<InputReceivedMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_INPUT_RECEIVED;

        $container->setSingleton(
            InputReceivedHandler::class,
            $handler = new Handler\InputReceivedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteDispatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCommandDispatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandDispatchedMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_DISPATCHED;

        $container->setSingleton(
            CommandDispatchedHandler::class,
            $handler = new Handler\CommandDispatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ThrowableCaughtHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishThrowableCaughtHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ThrowableCaughtMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_THROWABLE_CAUGHT;

        $container->setSingleton(
            ThrowableCaughtHandler::class,
            $handler = new Handler\ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteMatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCommandMatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandMatchedMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_MATCHED;

        $container->setSingleton(
            CommandMatchedHandler::class,
            $handler = new Handler\CommandMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteNotMatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCommandNotMatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CommandNotMatchedMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_COMMAND_NOT_MATCHED;

        $container->setSingleton(
            CommandNotMatchedHandler::class,
            $handler = new Handler\CommandNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the TerminatedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishExitedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ExitedMiddleware>[] $middleware */
        $middleware = $env::CLI_MIDDLEWARE_EXITED;

        $container->setSingleton(
            ExitedHandler::class,
            $handler = new Handler\ExitedHandler($container)
        );

        $handler->add(...$middleware);
    }
}
