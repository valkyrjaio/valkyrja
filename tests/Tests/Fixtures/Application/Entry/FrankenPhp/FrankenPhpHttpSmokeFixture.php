<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Entry\FrankenPhp;

use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;

use function ob_get_clean;
use function ob_get_level;
use function ob_start;

/**
 * Smoke-test FrankenPhpHttp subclass.
 *
 * Drives the real run() loop end to end — the request is built for a target
 * route and dispatched through the real handle() pipeline, which emits the
 * response through the PHP SAPI (as FrankenPHP's worker runtime would capture
 * it) — while doubling only the runtime-bound seams: bootstrap() returns the
 * pre-booted application, getRequest() targets the route, and
 * handleFrankenPhpRequest() invokes the handler once, capturing the emitted SAPI
 * output, and stops the loop.
 */
final class FrankenPhpHttpSmokeFixture extends FrankenPhpHttp
{
    public static ApplicationContract $app;

    public static string $requestUri = '/';

    public static string|null $sentBody = null;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$requestUri = '/';
        self::$sentBody   = null;
    }

    #[Override]
    public static function bootstrap(HttpConfigContract $config): ApplicationContract
    {
        return self::$app;
    }

    #[Override]
    public static function getRequest(): ServerRequestContract
    {
        return RequestFactory::fromGlobals(
            server: [
                'REQUEST_URI'    => self::$requestUri,
                'REQUEST_METHOD' => 'GET',
            ]
        );
    }

    #[Override]
    public static function handleFrankenPhpRequest(callable $handler): bool
    {
        // The request handler emits the response through the SAPI (echo the body,
        // then flush). Nest two buffers so the response's own flush lands in the
        // outer (captured) buffer, then drain everything opened so no output leaks.
        $baseline = ob_get_level();

        ob_start();
        ob_start();

        $handler();

        $output = '';

        while (ob_get_level() > $baseline) {
            $output = ((string) ob_get_clean()) . $output;
        }

        self::$sentBody = $output;

        return false;
    }
}
