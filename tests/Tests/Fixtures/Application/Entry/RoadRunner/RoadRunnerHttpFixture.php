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

namespace Valkyrja\Tests\Fixtures\Application\Entry\RoadRunner;

use Override;
use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\ServerRequest;

/**
 * Testable RoadRunnerHttp subclass.
 *
 * Overrides the runtime seams so run()'s worker loop can be driven without the
 * RoadRunner relay: bootstrap() returns an injected application, getWorker()
 * returns an injected worker, waitForRequest() returns queued requests (null
 * ends the loop), getRequestFromRoadRunnerRequest() returns a stub request, and
 * handle() records calls.
 */
final class RoadRunnerHttpFixture extends RoadRunnerHttp
{
    public static ApplicationContract $app;

    public static HttpWorker $worker;

    public static int $handleCallCount = 0;

    /** @var list<Request|null> */
    public static array $requests = [];

    public static int $waitForRequestCallCount = 0;

    public static int $getRequestFromRoadRunnerRequestCallCount = 0;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$handleCallCount                          = 0;
        self::$requests                                 = [];
        self::$waitForRequestCallCount                  = 0;
        self::$getRequestFromRoadRunnerRequestCallCount = 0;
    }

    #[Override]
    public static function bootstrap(HttpConfigContract $config, Env $env = new Env()): ApplicationContract
    {
        return self::$app;
    }

    #[Override]
    public static function handle(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): void
    {
        self::$handleCallCount++;
    }

    #[Override]
    public static function getWorker(): HttpWorker
    {
        return self::$worker;
    }

    #[Override]
    public static function waitForRequest(HttpWorker $worker): Request|null
    {
        $index = self::$waitForRequestCallCount++;

        return self::$requests[$index] ?? null;
    }

    #[Override]
    public static function getRequestFromRoadRunnerRequest(Request $roadRunnerRequest): ServerRequestContract
    {
        self::$getRequestFromRoadRunnerRequestCallCount++;

        return new ServerRequest();
    }
}
