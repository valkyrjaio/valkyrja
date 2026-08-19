<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Entry;

use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Route;

use function ob_get_clean;
use function ob_get_level;
use function ob_start;

/**
 * Records WorkerHttp's per-request containers and applications without the full HTTP provider stack.
 */
final class WorkerHttpFixture extends WorkerHttp
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
    public static function run(HttpConfigContract $config, int $requestCount = 1): void
    {
        $app  = self::bootstrap(config: $config);
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
     */
    #[Override]
    public static function bootstrapParentServices(ApplicationContract $app): void
    {
        // Counted so a test can assert bootstrap runs once, not once per request.
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
     */
    #[Override]
    public static function handleRequest(ContainerContract $container, ServerRequestContract $request): void
    {
        self::$handleRequestCallCount++;

        // The request handler sends the response, which echoes the body and
        // then calls ob_flush()/flush() — that pushes content up and out of a
        // single buffer. Nest two buffers so the response's own flush lands in
        // the outer (captured) buffer, then drain everything we opened so no
        // output leaks to stdout.
        $baseline = ob_get_level();

        ob_start();
        ob_start();

        parent::handleRequest($container, $request);

        $output = '';

        while (ob_get_level() > $baseline) {
            $output = ob_get_clean() . $output;
        }

        self::$requestResponses[] = $output;
    }
}
