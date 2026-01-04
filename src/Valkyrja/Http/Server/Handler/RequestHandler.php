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

namespace Valkyrja\Http\Server\Handler;

use Override;
use Throwable;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response as HttpResponse;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract as Contract;

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

class RequestHandler implements Contract
{
    public function __construct(
        protected ContainerContract $container = new Container(),
        protected RouterContract $router = new Router(),
        protected RequestReceivedHandlerContract $requestReceivedHandler = new RequestReceivedHandler(),
        protected ThrowableCaughtHandlerContract $throwableCaughtHandler = new ThrowableCaughtHandler(),
        protected SendingResponseHandlerContract $sendingResponseHandler = new SendingResponseHandler(),
        protected TerminatedHandlerContract $terminatedHandler = new TerminatedHandler(),
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    #[Override]
    public function handle(ServerRequestContract $request): ResponseContract
    {
        try {
            $response = $this->dispatchRouter($request);
        } catch (Throwable $throwable) {
            $response = $this->getResponseFromThrowable($throwable);
            $response = $this->throwableCaughtHandler->throwableCaught($request, $response, $throwable);
        }

        // Set the returned response in the container
        $this->container->setSingleton(ResponseContract::class, $response);

        return $response;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function send(ResponseContract $response): static
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
    public function terminate(ServerRequestContract $request, ResponseContract $response): void
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
    public function run(ServerRequestContract $request): void
    {
        // Handle the request, dispatch the after request middleware
        $response = $this->handle($request);
        // Dispatch the sending middleware
        $response = $this->sendingResponseHandler->sendingResponse($request, $response);

        // Set the returned response in the container
        $this->container->setSingleton(ResponseContract::class, $response);

        // Send the response
        $this->send($response);
        // Terminate the application
        $this->terminate($request, $response);
    }

    /**
     * Dispatch the request via the router.
     *
     * @param ServerRequestContract $request The request
     */
    protected function dispatchRouter(ServerRequestContract $request): ResponseContract
    {
        // Set the request object in the container
        $this->container->setSingleton(ServerRequestContract::class, $request);

        // Dispatch the before request handled middleware
        $requestAfterMiddleware = $this->requestReceivedHandler->requestReceived($request);

        // If the return value after middleware is a response return it
        if ($requestAfterMiddleware instanceof ResponseContract) {
            return $requestAfterMiddleware;
        }

        // Set the returned request in the container
        $this->container->setSingleton(ServerRequestContract::class, $requestAfterMiddleware);

        return $this->router->dispatch($requestAfterMiddleware);
    }

    /**
     * Get a response from a throwable.
     *
     * @param Throwable $throwable The exception
     *
     * @throws Throwable
     */
    protected function getResponseFromThrowable(Throwable $throwable): ResponseContract
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
     */
    protected function getDefaultErrorResponse(HttpException|null $httpException = null): ResponseContract
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
     */
    protected function finishSession(): void
    {
        if ($this->shouldCloseSession()) {
            $this->closeSession();
        }
    }

    /**
     * Determine if the session should be closed.
     */
    protected function shouldCloseSession(): bool
    {
        $sessionId = session_id();

        return $sessionId !== false && $sessionId !== '';
    }

    /**
     * Close the session.
     */
    protected function closeSession(): void
    {
        session_write_close();
    }

    /**
     * Finish the request.
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
     */
    protected function shouldUseFastcgiToFinishRequest(): bool
    {
        return function_exists('fastcgi_finish_request');
    }

    /**
     * Determine if the request should be finished with Litespeed.
     */
    protected function shouldUseLitespeedToFinishRequest(): bool
    {
        return function_exists('litespeed_finish_request');
    }

    /**
     * Determine if the request should be finished via closing the output buffers.
     */
    protected function shouldCloseOutputBuffersToFinishRequest(): bool
    {
        return ! in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true);
    }

    /**
     * Finish the request with Fastcgi.
     */
    protected function finishRequestWithFastcgi(): void
    {
        fastcgi_finish_request();
    }

    /**
     * Finish the request with Litespeed.
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
     */
    protected function closeOutputBuffersWithFlush(): void
    {
        ob_end_flush();
    }

    /**
     * End the output buffers with clean.
     */
    protected function closeOutputBuffersWithClean(): void
    {
        ob_end_clean();
    }

    /**
     * Finish the request for any scenario not previously caught.
     */
    protected function finishRequestForAllOtherTypes(): void
    {
    }
}
