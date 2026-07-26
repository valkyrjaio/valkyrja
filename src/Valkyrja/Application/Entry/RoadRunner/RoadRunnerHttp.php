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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

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

            $response = static::handleRoadRunnerRequest($app, $data, $request);

            static::respondToWorker($worker, $response);
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

    /**
     * Handle a framework request through an isolated child application and
     * return the resulting framework response.
     *
     * Mirrors the request handler's run() pipeline (handle, then the sending
     * response middleware) but returns the response for the worker to emit
     * instead of sending it through the PHP SAPI.
     */
    public static function handleRoadRunnerRequest(ApplicationContract $app, ContainerData $data, ServerRequestContract $request): ResponseContract
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
     * Write a framework response back out through the RoadRunner worker.
     */
    public static function respondToWorker(HttpWorker $worker, ResponseContract $response): void
    {
        $body = $response->getBody();
        $body->rewind();

        static::sendRoadRunnerResponse(
            $worker,
            $response->getStatusCode()->value,
            $body->getContents(),
            static::getHeadersForRoadRunnerResponse($response)
        );
    }

    /**
     * Marshal the RoadRunner header map from a framework response.
     *
     * RoadRunner expects each header name mapped to a list of its values; each
     * framework header (including a distinct Set-Cookie per cookie) contributes
     * one value line under its name.
     *
     * @return array<string, list<string>>
     */
    protected static function getHeadersForRoadRunnerResponse(ResponseContract $response): array
    {
        $headers = [];

        foreach ($response->getHeaders()->getAll() as $header) {
            $headers[$header->getName()][] = $header->getHeaderLine();
        }

        return $headers;
    }

    /**
     * Send the framework response through the RoadRunner worker.
     *
     * @param array<string, list<string>> $headers
     *
     * @codeCoverageIgnore Requires the RoadRunner worker runtime and relay.
     */
    protected static function sendRoadRunnerResponse(HttpWorker $worker, int $statusCode, string $body, array $headers): void
    {
        $worker->respond($statusCode, $body, $headers);
    }
}
