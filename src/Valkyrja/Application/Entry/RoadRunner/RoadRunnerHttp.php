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

namespace Valkyrja\Application\Entry\RoadRunner;

use Spiral\RoadRunner\Http\GlobalState;
use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Spiral\RoadRunner\Worker;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;
use Valkyrja\Application\Env\Env;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Stream\Stream;

class RoadRunnerHttp extends WorkerHttp
{
    /**
     * Run the RoadRunner app.
     *
     * @see https://docs.roadrunner.dev/docs/php-worker/worker
     */
    public static function run(HttpConfig $config, Env $env = new Env()): void
    {
        $app = static::bootstrap(
            config: $config,
            env: $env,
        );

        $container = $app->getContainer();
        $data      = $container->getData();

        $worker = static::getWorker();

        while (true) {
            $roadRunnerRequest = static::waitForRequest($worker);

            if ($roadRunnerRequest === null) {
                break;
            }

            $request = static::getRequestFromRoadRunnerRequest($roadRunnerRequest);

            static::handle($app, $data, $request);
        }
    }

    /**
     * Get the RoadRunner HTTP worker.
     *
     * @codeCoverageIgnore Requires the RoadRunner worker runtime and relay.
     */
    public static function getWorker(): HttpWorker
    {
        return new HttpWorker(
            Worker::create()
        );
    }

    /**
     * Wait for the next request from the RoadRunner worker.
     *
     * Returns null when the worker should stop.
     *
     * @codeCoverageIgnore Requires the RoadRunner worker runtime and relay.
     */
    public static function waitForRequest(HttpWorker $worker): Request|null
    {
        return $worker->waitRequest();
    }

    /**
     * Get the server request from a given RoadRunner request.
     */
    public static function getRequestFromRoadRunnerRequest(Request $roadRunnerRequest): ServerRequestContract
    {
        /** @var array<string, string|int|float|array<scalar>> $server */
        $server = GlobalState::enrichServerVars($roadRunnerRequest);

        $request = RequestFactory::fromGlobals(
            server: $server,
            query: $roadRunnerRequest->query,
            body: $roadRunnerRequest->getParsedBody(),
            cookies: $roadRunnerRequest->cookies,
            files: $roadRunnerRequest->uploads
        );

        $stream = new Stream();
        $stream->write($roadRunnerRequest->body);
        $stream->rewind();

        return $request
            ->withBody($stream);
    }
}
