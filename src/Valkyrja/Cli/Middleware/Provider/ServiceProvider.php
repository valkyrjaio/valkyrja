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
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Middleware\Handler\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler as CommandDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler as CommandMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler as CommandNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler as ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler as InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler as ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;

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
            InputReceivedHandlerContract::class,
            $handler = new InputReceivedHandler($container)
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
            CommandDispatchedHandlerContract::class,
            $handler = new CommandDispatchedHandler($container)
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
            ThrowableCaughtHandlerContract::class,
            $handler = new ThrowableCaughtHandler($container)
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
            CommandMatchedHandlerContract::class,
            $handler = new CommandMatchedHandler($container)
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
            CommandNotMatchedHandlerContract::class,
            $handler = new CommandNotMatchedHandler($container)
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
            ExitedHandlerContract::class,
            $handler = new ExitedHandler($container)
        );

        $handler->add(...$middleware);
    }
}
