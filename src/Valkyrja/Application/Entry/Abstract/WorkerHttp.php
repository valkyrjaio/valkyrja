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

namespace Valkyrja\Application\Entry\Abstract;

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\ChildApplication;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\ChildContainer;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

/**
 * HTTP entry point for persistent worker runtimes (FrankenPHP, RoadRunner, Swoole, etc.).
 *
 * Usage:
 *   $app = WorkerHttp::bootstrap($config);  // once — at worker startup
 *
 *   // Per request (inside the worker loop):
 *   WorkerHttp::run($app);
 *
 * bootstrap() performs the full bootstrap and force-resolves any services that
 * must live in the frozen parent container. run() creates an isolated child per
 * request so state never bleeds between requests.
 */
abstract class WorkerHttp extends App
{
    /**
     * Bootstrap the application once at worker startup.
     *
     * Call this once before the worker request loop begins. The returned
     * ApplicationContract is frozen after this call — its container must not
     * be written to again. Pass it to run() for every subsequent request.
     */
    public static function bootstrap(HttpConfig $config, Env $env = new Env()): ApplicationContract
    {
        $app = static::start(
            env: $env,
            config: $config,
        );

        $container = $app->getContainer();

        static::bootstrapThrowableHandler($app, $container);

        // Force-resolve services that must live in the parent's frozen map.
        // Anything not resolved here will be created fresh per request in the
        // child container, which is correct but incurs creation cost each time.
        static::bootstrapParentServices($app);

        return $app;
    }

    /**
     * Handle a single request using an isolated child application.
     *
     * Creates a child application and container for the request, sets the
     * request-scoped singletons on the child, dispatches the request, then
     * discards the child. The parent application is never mutated.
     */
    public static function handle(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): void
    {
        $childContainer = static::getChildContainer($app, $data);
        $childApp       = static::getChildApplication($app, $childContainer);

        static::bootstrapChildContainer($childApp, $childContainer);

        static::handleRequest($childContainer, $request);
    }

    /**
     * Get a child application for the request.
     */
    public static function getChildApplication(ApplicationContract $app, ContainerContract $container): ApplicationContract
    {
        return new ChildApplication($app, $container);
    }

    /**
     * Get a child container for the request.
     */
    public static function getChildContainer(ApplicationContract $app, ContainerData $data): ContainerContract
    {
        $parent = $app->getContainer();

        return new ChildContainer($parent, $data);
    }

    /**
     * Bootstrap a child container with the request-scoped singletons.
     */
    public static function bootstrapChildContainer(ApplicationContract $app, ContainerContract $container): void
    {
        $container->setSingleton(ApplicationContract::class, $app);
        $container->setSingleton(ContainerContract::class, $container);
    }

    /**
     * Handle a single request.
     */
    public static function handleRequest(ContainerContract $container, ServerRequestContract $request): void
    {
        $handler = $container->getSingleton(RequestHandlerContract::class);
        $handler->run($request);
    }

    /**
     * Get the current request.
     */
    public static function getRequest(): ServerRequestContract
    {
        return RequestFactory::fromGlobals();
    }

    /**
     * Force-resolve services that must be pre-built in the parent container.
     *
     * Override in subclasses to eagerly resolve additional services (e.g. the
     * route matcher) so they are cached in the frozen parent rather than being
     * re-created on every request's child container.
     */
    public static function bootstrapParentServices(ApplicationContract $app): void
    {
        // Subclasses may force-resolve expensive shared services here, e.g.:
        $app->getContainer()->getSingleton(RouteCollectionContract::class);
    }
}
