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

namespace Valkyrja\Tests\Fixtures\Application\Entry\OpenSwoole;

use OpenSwoole\Http\Server;
use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\ServerRequest;

/**
 * Testable OpenSwooleHttp subclass.
 *
 * Overrides the runtime seams so run() can be driven without the OpenSwoole
 * event loop: bootstrap() returns an injected application, handle() records
 * calls, and startServer() records that the (blocking) start was reached
 * instead of actually starting the server.
 */
final class OpenSwooleHttpFixture extends OpenSwooleHttp
{
    public static ApplicationContract $app;

    public static int $handleCallCount = 0;

    public static bool $serverStarted = false;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$handleCallCount = 0;
        self::$serverStarted   = false;
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
    public static function getRequest(): ServerRequestContract
    {
        return new ServerRequest();
    }

    #[Override]
    public static function startServer(Server $server): void
    {
        self::$serverStarted = true;
    }
}
