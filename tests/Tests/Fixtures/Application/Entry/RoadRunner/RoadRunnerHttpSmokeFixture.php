<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Entry\RoadRunner;

use Override;
use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;

/**
 * Drives RoadRunnerHttp's real run() loop end to end, doubling only the relay-bound seams.
 */
final class RoadRunnerHttpSmokeFixture extends RoadRunnerHttp
{
    public static ApplicationContract $app;

    public static HttpWorker $worker;

    /** @var list<Request|null> */
    public static array $requests = [];

    public static int $waitForRequestCallCount = 0;

    public static int|null $sentStatus = null;

    public static string|null $sentBody = null;

    /** @var array<string, list<string>> */
    public static array $sentHeaders = [];

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$requests                = [];
        self::$waitForRequestCallCount = 0;
        self::$sentStatus              = null;
        self::$sentBody                = null;
        self::$sentHeaders             = [];
    }

    #[Override]
    public static function bootstrap(HttpConfigContract $config): ApplicationContract
    {
        return self::$app;
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
