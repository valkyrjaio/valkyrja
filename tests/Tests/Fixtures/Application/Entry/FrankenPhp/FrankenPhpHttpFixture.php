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

namespace Valkyrja\Tests\Fixtures\Application\Entry\FrankenPhp;

use Override;
use RuntimeException;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\ServerRequest;

/**
 * Testable FrankenPhpHttp subclass.
 *
 * Overrides the runtime seams so run()'s request loop can be driven without the
 * FrankenPHP worker runtime: bootstrap() returns an injected application,
 * handle() records calls (and optionally throws to exercise the catch branch),
 * getMaxRequests() returns a configured bound, and handleFrankenPhpRequest()
 * invokes the handler and returns queued keep-running values.
 */
final class FrankenPhpHttpFixture extends FrankenPhpHttp
{
    public static ApplicationContract $app;

    public static int $handleCallCount = 0;

    public static bool $handleThrows = false;

    public static int $maxRequests = 0;

    /** @var list<bool> */
    public static array $keepRunningReturns = [];

    public static int $handleFrankenPhpRequestCallCount = 0;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$handleCallCount                  = 0;
        self::$handleThrows                     = false;
        self::$maxRequests                      = 0;
        self::$keepRunningReturns               = [];
        self::$handleFrankenPhpRequestCallCount = 0;
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

        if (self::$handleThrows) {
            throw new RuntimeException('Handle failure.');
        }
    }

    #[Override]
    public static function getRequest(): ServerRequestContract
    {
        return new ServerRequest();
    }

    #[Override]
    public static function getMaxRequests(): int
    {
        return self::$maxRequests;
    }

    #[Override]
    public static function handleFrankenPhpRequest(callable $handler): bool
    {
        $index = self::$handleFrankenPhpRequestCallCount++;

        $handler();

        return self::$keepRunningReturns[$index] ?? false;
    }
}
