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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;

/**
 * Testable RoadRunnerHttp subclass.
 *
 * Overrides the runtime seams so run()'s worker loop can be driven without the
 * RoadRunner relay: bootstrap() returns an injected application, getWorker()
 * returns an injected worker, waitForRequest() returns queued requests (null
 * ends the loop), getRequestFromRoadRunnerRequest() returns a stub request,
 * handleRoadRunnerRequest() returns an injected framework response, and
 * sendRoadRunnerResponse() records the emitted status/body/headers instead of
 * writing back to a non-live worker.
 */
final class RoadRunnerHttpFixture extends RoadRunnerHttp
{
    public static ApplicationContract $app;

    public static HttpWorker $worker;

    public static int $handleRoadRunnerRequestCallCount = 0;

    public static ResponseContract $frameworkResponse;

    /** @var list<Request|null> */
    public static array $requests = [];

    public static int $waitForRequestCallCount = 0;

    public static int $getRequestFromRoadRunnerRequestCallCount = 0;

    public static int|null $sentStatus = null;

    public static string|null $sentBody = null;

    /** @var array<string, list<string>> */
    public static array $sentHeaders = [];

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$handleRoadRunnerRequestCallCount         = 0;
        self::$frameworkResponse                        = new Response();
        self::$requests                                 = [];
        self::$waitForRequestCallCount                  = 0;
        self::$getRequestFromRoadRunnerRequestCallCount = 0;
        self::$sentStatus                               = null;
        self::$sentBody                                 = null;
        self::$sentHeaders                              = [];
    }

    #[Override]
    public static function bootstrap(HttpConfigContract $config): ApplicationContract
    {
        return self::$app;
    }

    #[Override]
    public static function handleRoadRunnerRequest(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): ResponseContract
    {
        self::$handleRoadRunnerRequestCallCount++;

        return self::$frameworkResponse;
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

    /**
     * @param array<string, list<string>> $headers
     */
    #[Override]
    protected static function sendRoadRunnerResponse(HttpWorker $worker, int $statusCode, string $body, array $headers): void
    {
        self::$sentStatus  = $statusCode;
        self::$sentBody    = $body;
        self::$sentHeaders = $headers;
    }
}
