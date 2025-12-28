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

namespace Valkyrja\Http\Server;

use Override;
use Throwable;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\Response as HttpResponse;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Dispatcher\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler as Contract;

use function count;
use function defined;
use function fastcgi_finish_request;
use function function_exists;
use function in_array;
use function litespeed_finish_request;
use function ob_end_clean;
use function ob_end_flush;
use function ob_get_status;
use function session_id;
use function session_write_close;

use const PHP_OUTPUT_HANDLER_CLEANABLE;
use const PHP_OUTPUT_HANDLER_FLUSHABLE;
use const PHP_OUTPUT_HANDLER_REMOVABLE;
use const PHP_SAPI;

/**
 * Class RequestHandler.
 *
 * @author Melech Mizrachi
 */
class RequestHandler implements Contract
{
    /**
     * RequestHandler constructor.
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Router $router = new \Valkyrja\Http\Routing\Dispatcher\Router(),
        protected RequestReceivedHandler $requestReceivedHandler = new Middleware\Handler\RequestReceivedHandler(),
        protected ThrowableCaughtHandler $throwableCaughtHandler = new Middleware\Handler\ThrowableCaughtHandler(),
        protected SendingResponseHandler $sendingResponseHandler = new Middleware\Handler\SendingResponseHandler(),
        protected TerminatedHandler $terminatedHandler = new Middleware\Handler\TerminatedHandler(),
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    #[Override]
    public function handle(ServerRequest $request): Response
    {
        try {
            $response = $this->dispatchRouter($request);
        } catch (Throwable $throwable) {
            $response = $this->getResponseFromThrowable($throwable);
            $response = $this->throwableCaughtHandler->throwableCaught($request, $response, $throwable);
        }

        // Set the returned response in the container
        $this->container->setSingleton(Response::class, $response);

        return $response;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function send(Response $response): static
    {
        $response->send();

        $this->finishSession();
        $this->finishRequest();

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function terminate(ServerRequest $request, Response $response): void
    {
        // Dispatch the terminable middleware
        $this->terminatedHandler->terminated($request, $response);
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    #[Override]
    public function run(ServerRequest $request): void
    {
        // Handle the request, dispatch the after request middleware
        $response = $this->handle($request);
        // Dispatch the sending middleware
        $response = $this->sendingResponseHandler->sendingResponse($request, $response);

        // Set the returned response in the container
        $this->container->setSingleton(Response::class, $response);

        // Send the response
        $this->send($response);
        // Terminate the application
        $this->terminate($request, $response);
    }

    /**
     * Dispatch the request via the router.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    protected function dispatchRouter(ServerRequest $request): Response
    {
        // Set the request object in the container
        $this->container->setSingleton(ServerRequest::class, $request);

        // Dispatch the before request handled middleware
        $requestAfterMiddleware = $this->requestReceivedHandler->requestReceived($request);

        // If the return value after middleware is a response return it
        if ($requestAfterMiddleware instanceof Response) {
            return $requestAfterMiddleware;
        }

        // Set the returned request in the container
        $this->container->setSingleton(ServerRequest::class, $requestAfterMiddleware);

        return $this->router->dispatch($requestAfterMiddleware);
    }

    /**
     * Get a response from a throwable.
     *
     * @param Throwable $throwable The exception
     *
     * @throws Throwable
     *
     * @return Response
     */
    protected function getResponseFromThrowable(Throwable $throwable): Response
    {
        if ($this->debug) {
            throw $throwable;
        }

        // If no response has been set and there is a template with the error code
        if ($throwable instanceof HttpException) {
            return $throwable->getResponse()
                ?? $this->getDefaultErrorResponse($throwable);
        }

        return $this->getDefaultErrorResponse();
    }

    /**
     * Get the default exception response.
     *
     * @param HttpException|null $httpException [optional] The Http exception
     *
     * @return Response
     */
    protected function getDefaultErrorResponse(HttpException|null $httpException = null): Response
    {
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;

        $body = new Stream();
        $body->write('Unknown Server Error Occurred');
        $body->rewind();

        if ($httpException !== null) {
            $statusCode = $httpException->getStatusCode();
            $body->write('Unknown Server Error Occurred - ' . $httpException->getTraceCode());
            $body->rewind();
        }

        return new HttpResponse(
            body: $body,
            statusCode: $statusCode
        );
    }

    /**
     * Finish a session if it is active.
     *
     * @return void
     */
    protected function finishSession(): void
    {
        if ($this->shouldCloseSession()) {
            $this->closeSession();
        }
    }

    /**
     * Determine if the session should be closed.
     *
     * @return bool
     */
    protected function shouldCloseSession(): bool
    {
        $sessionId = session_id();

        return $sessionId !== false && $sessionId !== '';
    }

    /**
     * Close the session.
     *
     * @return void
     */
    protected function closeSession(): void
    {
        session_write_close();
    }

    /**
     * Finish the request.
     *
     * @return void
     */
    protected function finishRequest(): void
    {
        // If fastcgi is enabled
        if ($this->shouldUseFastcgiToFinishRequest()) {
            // Use it to finish the request
            $this->finishRequestWithFastcgi();
        } elseif ($this->shouldUseLitespeedToFinishRequest()) {
            // If litespeed is enabled
            // Use it to finish the request
            $this->finishRequestWithLitespeed();
        } elseif ($this->shouldCloseOutputBuffersToFinishRequest()) {
            // Otherwise if this isn't a cli request
            // Use an internal method to finish the request
            $this->closeOutputBuffers(0, true);
        } else {
            $this->finishRequestForAllOtherTypes();
        }
    }

    /**
     * Determine if the request should be finished with Fastcgi.
     *
     * @return bool
     */
    protected function shouldUseFastcgiToFinishRequest(): bool
    {
        return function_exists('fastcgi_finish_request');
    }

    /**
     * Determine if the request should be finished with Litespeed.
     *
     * @return bool
     */
    protected function shouldUseLitespeedToFinishRequest(): bool
    {
        return function_exists('litespeed_finish_request');
    }

    /**
     * Determine if the request should be finished via closing the output buffers.
     *
     * @return bool
     */
    protected function shouldCloseOutputBuffersToFinishRequest(): bool
    {
        return ! in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true);
    }

    /**
     * Finish the request with Fastcgi.
     *
     * @return void
     */
    protected function finishRequestWithFastcgi(): void
    {
        fastcgi_finish_request();
    }

    /**
     * Finish the request with Litespeed.
     *
     * @return void
     */
    protected function finishRequestWithLitespeed(): void
    {
        /** @psalm-suppress UndefinedFunction */
        litespeed_finish_request();
    }

    /**
     * Cleans or flushes output buffers up to target level.
     * Resulting level can be greater than target level if a non-removable
     * buffer has been encountered.
     *
     * @param int  $targetLevel The target output buffering level
     * @param bool $flush       Whether to flush or clean the buffers
     *
     * @return void
     */
    protected function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = $this->outputBuffersGetStatus();
        $level  = count($status);

        $flushOrCleanFlag = $flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE;
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ? PHP_OUTPUT_HANDLER_REMOVABLE | $flushOrCleanFlag : -1;

        while (
            $level-- > $targetLevel
            && ($s = $status[$level])
            && ($s['del'] ?? (! isset($s['flags']) || $flags === ($s['flags'] & $flags)))
        ) {
            if ($flush) {
                $this->closeOutputBuffersWithFlush();
            } else {
                $this->closeOutputBuffersWithClean();
            }
        }
    }

    /**
     * Get the status of the output buffers.
     *
     * @return array<int, ?array{chunk_size: int, buffer_size: int, buffer_used: int, flags?: int, level?: int, type?: int, del?: int, name?: string}>
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    protected function outputBuffersGetStatus(): array
    {
        return ob_get_status(true);
    }

    /**
     * End the output buffers with flush.
     *
     * @return void
     */
    protected function closeOutputBuffersWithFlush(): void
    {
        ob_end_flush();
    }

    /**
     * End the output buffers with clean.
     *
     * @return void
     */
    protected function closeOutputBuffersWithClean(): void
    {
        ob_end_clean();
    }

    /**
     * Finish the request for any scenario not previously caught.
     *
     * @return void
     */
    protected function finishRequestForAllOtherTypes(): void
    {
    }
}
