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
     */
    public static function onRequest(ApplicationContract $app, ContainerData $data, Request $request, Response $response): void
    {
        static::handle($app, $data, static::getRequest());
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
}
