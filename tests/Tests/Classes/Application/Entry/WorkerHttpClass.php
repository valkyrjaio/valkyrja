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

namespace Valkyrja\Tests\Classes\Application\Entry;

use Override;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Route;

/**
 * Testable WorkerHttp subclass.
 *
 * Overrides:
 *   - bootstrapParentServices(): tracks call count; skips real service resolution
 *     so tests don't require the full HTTP provider stack.
 *   - getChildContainer(): records every child container created per request.
 *   - getChildApplication(): records every child application created per request.
 *   - handleRequest(): no-op; tests verify isolation, not dispatch.
 */
final class WorkerHttpClass extends WorkerHttp
{
    /** @var list<ContainerContract> Containers created across all handle() calls */
    public static array $childContainers = [];

    /** @var list<ApplicationContract> Applications created across all handle() calls */
    public static array $childApplications = [];

    /** @var string[] responses created across all handleRequest() calls */
    public static array $requestResponses = [];

    /** Number of times bootstrapParentServices() has been called */
    public static int $bootstrapParentServicesCallCount = 0;

    /** Number of times handleRequest() has been called */
    public static int $handleRequestCallCount = 0;

    /** Number of times handleRoute() has been called */
    public static int $handleRouteCallCount = 0;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$childContainers                  = [];
        self::$childApplications                = [];
        self::$requestResponses                 = [];
        self::$bootstrapParentServicesCallCount = 0;
        self::$handleRequestCallCount           = 0;
        self::$handleRouteCallCount             = 0;
    }

    /**
     * Run the faux worker app for a given number of requests.
     *
     * Mirrors the real worker entry class pattern: bootstrap once, capture data,
     * build a handler closure, then invoke it requestCount times.
     */
    public static function run(HttpConfig $config, int $requestCount = 1, Env $env = new Env()): void
    {
        $app  = self::bootstrap(config: $config, env: $env);
        $data = $app->getContainer()->getData();

        $handler = static function () use ($app, $data): void {
            self::handle($app, $data, self::getRequest());
        };

        for ($i = 0; $i < $requestCount; $i++) {
            $handler();
        }
    }

    /**
     * @inheritDoc
     *
     * No-op: skips CollectionContract resolution so tests run without the full
     * HTTP provider stack. Tracks the call count so tests can assert it was
     * called exactly once (during bootstrap, not per request).
     */
    #[Override]
    public static function bootstrapParentServices(ApplicationContract $app): void
    {
        self::$bootstrapParentServicesCallCount++;

        parent::bootstrapParentServices($app);

        $collection = $app->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/',
                name: 'home',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    /**
     * Handle the route.
     */
    public static function handleRoute(): TextResponse
    {
        self::$handleRouteCallCount++;

        return new TextResponse('Hello World!');
    }

    /**
     * @inheritDoc
     *
     * Records the created container before returning it.
     */
    #[Override]
    public static function getChildContainer(ApplicationContract $app, ContainerData $data): ContainerContract
    {
        $container = parent::getChildContainer($app, $data);

        self::$childContainers[] = $container;

        return $container;
    }

    /**
     * @inheritDoc
     *
     * Records the created child application before returning it.
     */
    #[Override]
    public static function getChildApplication(ApplicationContract $app, ContainerContract $container): ApplicationContract
    {
        $childApp = parent::getChildApplication($app, $container);

        self::$childApplications[] = $childApp;

        return $childApp;
    }

    /**
     * @inheritDoc
     *
     * No-op: tests verify isolation, not actual request dispatch.
     */
    #[Override]
    public static function handleRequest(ContainerContract $container, ServerRequestContract $request): void
    {
        self::$handleRequestCallCount++;

        ob_start();
        parent::handleRequest($container, $request);

        self::$requestResponses[] = (string) ob_get_clean();
    }
}
