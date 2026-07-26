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

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Http\Server;
use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response as FrameworkResponse;

/**
 * Testable OpenSwooleHttp subclass.
 *
 * Overrides the runtime seams so the entry can be driven without the OpenSwoole
 * event loop or a live connection: bootstrap() returns an injected application,
 * startServer() records that the (blocking) start was reached, handleSwooleRequest()
 * returns an injected framework response, and the request/response I/O seams
 * (rawContent()/status()/header()/end()) record their arguments instead of
 * touching a non-live OpenSwoole request or response.
 */
final class OpenSwooleHttpFixture extends OpenSwooleHttp
{
    public static ApplicationContract $app;

    public static bool $serverStarted = false;

    public static int $handleSwooleRequestCallCount = 0;

    public static ResponseContract $frameworkResponse;

    public static string $rawContent = '';

    /** @var array{statusCode: int, reasonPhrase: string}|null */
    public static array|null $sentStatus = null;

    /** @var list<array{name: string, value: string}> */
    public static array $sentHeaders = [];

    public static string|null $sentBody = null;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$serverStarted                = false;
        self::$handleSwooleRequestCallCount = 0;
        self::$frameworkResponse            = new FrameworkResponse();
        self::$rawContent                   = '';
        self::$sentStatus                   = null;
        self::$sentHeaders                  = [];
        self::$sentBody                     = null;
    }

    #[Override]
    public static function bootstrap(HttpConfigContract $config, Env $env = new Env()): ApplicationContract
    {
        return self::$app;
    }

    #[Override]
    public static function handleSwooleRequest(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): ResponseContract
    {
        self::$handleSwooleRequestCallCount++;

        return self::$frameworkResponse;
    }

    #[Override]
    public static function startServer(Server $server): void
    {
        self::$serverStarted = true;
    }

    #[Override]
    protected static function getContentFromSwooleRequest(Request $request): string
    {
        return self::$rawContent;
    }

    #[Override]
    protected static function sendSwooleStatus(Response $response, int $statusCode, string $reasonPhrase): void
    {
        self::$sentStatus = [
            'statusCode'   => $statusCode,
            'reasonPhrase' => $reasonPhrase,
        ];
    }

    #[Override]
    protected static function sendSwooleHeader(Response $response, string $name, string $value): void
    {
        self::$sentHeaders[] = [
            'name'  => $name,
            'value' => $value,
        ];
    }

    #[Override]
    protected static function sendSwooleBody(Response $response, string $body): void
    {
        self::$sentBody = $body;
    }
}
