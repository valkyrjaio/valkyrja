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

namespace Valkyrja\Application\Entry\OpenSwoole;

use Closure;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Http\Server;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

use function str_replace;
use function strtoupper;

class OpenSwooleHttp extends WorkerHttp
{
    /**
     * Run the Swoole app.
     *
     * @see https://openswoole.com/
     */
    public static function run(HttpConfig $config, Env $env = new Env()): void
    {
        $app = static::bootstrap(
            config: $config,
            env: $env,
        );

        $container = $app->getContainer();
        $data      = $container->getData();

        $server = static::getSwooleServer();

        static::registerHandlers($server, $app, $data);

        static::startServer($server);
    }

    /**
     * Get the Swoole server.
     */
    public static function getSwooleServer(): Server
    {
        return new Server('127.0.0.1', 9501);
    }

    /**
     * Register the Swoole server event handlers.
     */
    public static function registerHandlers(Server $server, ApplicationContract $app, ContainerData $data): void
    {
        $server->on('start', [static::class, 'onStart']);
        $server->on('request', static::getRequestHandler($app, $data));
    }

    /**
     * Get the request handler closure for the Swoole server.
     */
    public static function getRequestHandler(ApplicationContract $app, ContainerData $data): Closure
    {
        return static function (Request $request, Response $response) use ($app, $data): void {
            static::onRequest($app, $data, $request, $response);
        };
    }

    /**
     * Handle a single Swoole request.
     *
     * Converts the OpenSwoole request into a framework request, dispatches it
     * through an isolated child application, and writes the framework response
     * back out through the OpenSwoole response.
     */
    public static function onRequest(ApplicationContract $app, ContainerData $data, Request $request, Response $response): void
    {
        $serverRequest = static::getRequestFromSwooleRequest($request);

        $frameworkResponse = static::handleSwooleRequest($app, $data, $serverRequest);

        static::emitSwooleResponse($frameworkResponse, $response);
    }

    /**
     * Get the framework server request from a given OpenSwoole request.
     */
    public static function getRequestFromSwooleRequest(Request $request): ServerRequestContract
    {
        $server = static::getServerParamsFromSwooleRequest($request);

        /** @var array<array-key, mixed> $query */
        $query = $request->get ?? [];
        /** @var array<array-key, mixed> $body */
        $body = $request->post ?? [];
        /** @var array<string, string|null> $cookies */
        $cookies = $request->cookie ?? [];
        /** @var array<array-key, mixed> $files */
        $files = $request->files ?? [];

        $serverRequest = RequestFactory::fromGlobals(
            server: $server,
            query: $query,
            body: $body,
            cookies: $cookies,
            files: $files,
        );

        $stream = new Stream();
        $stream->write(static::getContentFromSwooleRequest($request));
        $stream->rewind();

        return $serverRequest
            ->withBody($stream);
    }

    /**
     * Handle a framework request through an isolated child application and
     * return the resulting framework response.
     *
     * Mirrors the request handler's run() pipeline (handle, then the sending
     * response middleware) but returns the response for the worker to emit
     * instead of sending it through the PHP SAPI.
     */
    public static function handleSwooleRequest(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): ResponseContract
    {
        $childContainer = static::getChildContainer($app, $data);
        $childApp       = static::getChildApplication($app, $childContainer);

        static::bootstrapChildContainer($childApp, $childContainer);

        $handler  = $childContainer->getSingleton(RequestHandlerContract::class);
        $response = $handler->handle($request);

        $sendingResponseHandler = $childContainer->getSingleton(SendingResponseHandlerContract::class);
        $response               = $sendingResponseHandler->sendingResponse($request, $response);

        $childContainer->setSingleton(ResponseContract::class, $response);

        $handler->terminate($request, $response);

        return $response;
    }

    /**
     * Write a framework response back out through the OpenSwoole response.
     */
    public static function emitSwooleResponse(ResponseContract $response, Response $swooleResponse): void
    {
        static::sendSwooleStatus(
            $swooleResponse,
            $response->getStatusCode()->value,
            $response->getReasonPhrase()
        );

        foreach ($response->getHeaders()->getAll() as $header) {
            static::sendSwooleHeader($swooleResponse, $header->getName(), $header->getHeaderLine());
        }

        $body = $response->getBody();
        $body->rewind();

        static::sendSwooleBody($swooleResponse, $body->getContents());
    }

    /**
     * Handle the Swoole server start event.
     */
    public static function onStart(Server $server): void
    {
        // The OpenSwoole http server has started.
    }

    /**
     * Start the Swoole server.
     *
     * @codeCoverageIgnore The OpenSwoole event loop blocks indefinitely and cannot run under test.
     */
    public static function startServer(Server $server): void
    {
        $server->start();
    }

    /**
     * Marshal a $_SERVER-style params array from an OpenSwoole request.
     *
     * OpenSwoole exposes lowercase server keys (e.g. `request_uri`) and keeps
     * request headers in a separate lowercase map, whereas the framework
     * expects PHP's uppercase `$_SERVER` conventions with headers folded in as
     * `HTTP_*` (and `CONTENT_TYPE`/`CONTENT_LENGTH`) entries.
     *
     * @return array<string, string|int|float|array<scalar>>
     */
    protected static function getServerParamsFromSwooleRequest(Request $request): array
    {
        $server = [];

        /** @var array<array-key, string|int|float|array<scalar>> $swooleServer */
        $swooleServer = $request->server ?? [];

        foreach ($swooleServer as $key => $value) {
            $server[strtoupper((string) $key)] = $value;
        }

        /** @var array<array-key, string> $swooleHeaders */
        $swooleHeaders = $request->header ?? [];

        foreach ($swooleHeaders as $name => $value) {
            $normalizedName = strtoupper(str_replace('-', '_', (string) $name));

            if ($normalizedName === 'CONTENT_TYPE' || $normalizedName === 'CONTENT_LENGTH') {
                $server[$normalizedName] = $value;

                continue;
            }

            $server['HTTP_' . $normalizedName] = $value;
        }

        return $server;
    }

    /**
     * Get the raw request body content from an OpenSwoole request.
     *
     * @codeCoverageIgnore The raw content is only available for a live OpenSwoole request.
     */
    protected static function getContentFromSwooleRequest(Request $request): string
    {
        return (string) $request->rawContent();
    }

    /**
     * Send the status line through the OpenSwoole response.
     *
     * @codeCoverageIgnore The OpenSwoole response is only writable for a live request.
     */
    protected static function sendSwooleStatus(Response $response, int $statusCode, string $reasonPhrase): void
    {
        $response->status($statusCode, $reasonPhrase);
    }

    /**
     * Send a single header through the OpenSwoole response.
     *
     * @codeCoverageIgnore The OpenSwoole response is only writable for a live request.
     */
    protected static function sendSwooleHeader(Response $response, string $name, string $value): void
    {
        $response->header($name, $value);
    }

    /**
     * Send the response body through the OpenSwoole response.
     *
     * @codeCoverageIgnore The OpenSwoole response is only writable for a live request.
     */
    protected static function sendSwooleBody(Response $response, string $body): void
    {
        $response->end($body);
    }
}
