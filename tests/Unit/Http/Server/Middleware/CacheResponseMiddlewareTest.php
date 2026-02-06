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

namespace Valkyrja\Tests\Unit\Http\Server\Middleware;

use JsonException;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Message\Response\HtmlResponse;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Message\Response\XmlResponse;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Time\Time;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function md5;

class CacheResponseMiddlewareTest extends TestCase
{
    /**
     * @var non-empty-string
     */
    protected string $filePath;

    protected function setUp(): void
    {
        $this->filePath = Directory::storagePath('app');
    }

    public function testThroughHandler(): void
    {
        $container  = new Container();

        $container->setCallable(CacheResponseMiddleware::class, fn () => new CacheResponseMiddleware($this->filePath));

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);
        $terminatedHandler = new TerminatedHandler($container);
        $terminatedHandler->add(CacheResponseMiddleware::class);

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $beforeResponse = $beforeHandler->requestReceived($request);

        $terminatedHandler->terminated($request, $response);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(RequestContract::class, $beforeResponse);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTerminated = $beforeHandler->requestReceived($request);

        // Test that a subsequent request gets a response from cache
        self::assertInstanceOf(ResponseContract::class, $beforeResponseAfterTerminated);

        // Write a bad cache
        file_put_contents($this->getCachePathForRequest($request), '<?php return new \stdClass();');

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseWithBadCache = $beforeHandler->requestReceived($request);

        // Test that a subsequent request gets a request when the cached response is not valid
        self::assertInstanceOf(RequestContract::class, $beforeResponseWithBadCache);

        $terminatedHandler = new TerminatedHandler($container);
        $terminatedHandler->add(CacheResponseMiddleware::class);

        $terminatedHandler->terminated($request, $response);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTerminatedAfterBadCache = $beforeHandler->requestReceived($request);

        // Test that cache got reset successfully
        self::assertInstanceOf(ResponseContract::class, $beforeResponseAfterTerminatedAfterBadCache);

        // Freeze time to a time much later than ttl
        Time::freeze(Time::get() + 1801);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTtlExpired = $beforeHandler->requestReceived($request);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(RequestContract::class, $beforeResponseAfterTtlExpired);

        // Unfreeze for future tests
        Time::unfreeze();

        @unlink($this->getCachePathForRequest($request));
    }

    public function testDirectly(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $beforeResponse = $middleware->requestReceived($request, $beforeHandler);

        $middleware->terminated($request, $response, $terminatedHandler);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(RequestContract::class, $beforeResponse);

        $beforeHandler = new RequestReceivedHandler();

        $beforeResponseAfterTerminated = $middleware->requestReceived($request, $beforeHandler);

        // Test that a subsequent request gets a response from cache
        self::assertInstanceOf(ResponseContract::class, $beforeResponseAfterTerminated);

        // Write a bad cache
        file_put_contents($this->getCachePathForRequest($request), '<?php return new \stdClass();');

        $beforeHandler = new RequestReceivedHandler();

        $beforeResponseWithBadCache = $middleware->requestReceived($request, $beforeHandler);

        // Test that a subsequent request gets a request when the cached response is not valid
        self::assertInstanceOf(RequestContract::class, $beforeResponseWithBadCache);

        $middleware->terminated($request, $response, $terminatedHandler);

        $beforeResponseAfterTerminatedAfterBadCache = $middleware->requestReceived($request, $beforeHandler);

        // Test that cache got reset successfully
        self::assertInstanceOf(ResponseContract::class, $beforeResponseAfterTerminatedAfterBadCache);

        // Freeze time to a time much later than ttl
        Time::freeze(Time::get() + 1801);

        $beforeResponseAfterTtlExpired = $middleware->requestReceived($request, $beforeHandler);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(RequestContract::class, $beforeResponseAfterTtlExpired);

        // Unfreeze for future tests
        Time::unfreeze();

        @unlink($this->getCachePathForRequest($request));
    }

    public function testResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/response-test'));
        $response = Response::create('Test content', StatusCode::OK);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(Response::class, $cachedResponse);
        self::assertSame(StatusCode::OK, $cachedResponse->getStatusCode());
        self::assertSame('Test content', (string) $cachedResponse->getBody());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testEmptyResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/empty-response-test'));
        $response = new EmptyResponse();

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(EmptyResponse::class, $cachedResponse);
        self::assertSame(StatusCode::NO_CONTENT, $cachedResponse->getStatusCode());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testHtmlResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $htmlContent = '<html lang="en"><body><h1>Test</h1></body></html>';
        $request     = new ServerRequest(uri: new Uri(path: '/html-response-test'));
        $response    = new HtmlResponse($htmlContent, StatusCode::OK);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(HtmlResponse::class, $cachedResponse);
        self::assertSame(StatusCode::OK, $cachedResponse->getStatusCode());
        self::assertSame($htmlContent, (string) $cachedResponse->getBody());

        @unlink($this->getCachePathForRequest($request));
    }

    /**
     * @throws JsonException
     */
    public function testJsonResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $jsonData = ['key' => 'value', 'nested' => ['foo' => 'bar']];
        $request  = new ServerRequest(uri: new Uri(path: '/json-response-test'));
        $response = new JsonResponse($jsonData, StatusCode::OK);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(JsonResponse::class, $cachedResponse);
        self::assertSame(StatusCode::OK, $cachedResponse->getStatusCode());
        self::assertSame($jsonData, $cachedResponse->getBodyAsJson());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testRedirectResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $redirectUri = new Uri(path: '/redirect-destination');
        $request     = new ServerRequest(uri: new Uri(path: '/redirect-response-test'));
        $response    = new RedirectResponse($redirectUri, StatusCode::FOUND);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(RedirectResponse::class, $cachedResponse);
        self::assertSame(StatusCode::FOUND, $cachedResponse->getStatusCode());
        self::assertSame('/redirect-destination', $cachedResponse->getUri()->getPath());
        self::assertSame('/redirect-destination', $cachedResponse->getHeaderLine(HeaderName::LOCATION));

        @unlink($this->getCachePathForRequest($request));
    }

    public function testTextResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $textContent = 'Plain text content';
        $request     = new ServerRequest(uri: new Uri(path: '/text-response-test'));
        $response    = new TextResponse($textContent, StatusCode::OK);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(TextResponse::class, $cachedResponse);
        self::assertSame(StatusCode::OK, $cachedResponse->getStatusCode());
        self::assertSame($textContent, (string) $cachedResponse->getBody());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testXmlResponseClassWorks(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $xmlContent = '<?xml version="1.0"?><root><item>Test</item></root>';
        $request    = new ServerRequest(uri: new Uri(path: '/xml-response-test'));
        $response   = new XmlResponse($xmlContent, StatusCode::OK);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(XmlResponse::class, $cachedResponse);
        self::assertSame(StatusCode::OK, $cachedResponse->getStatusCode());
        self::assertSame($xmlContent, (string) $cachedResponse->getBody());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testResponseWithHeaders(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $headers  = [new Header('X-Custom-Header', 'custom-value')];
        $request  = new ServerRequest(uri: new Uri(path: '/headers-response-test'));
        $response = Response::create('Content with headers', StatusCode::OK, $headers);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(Response::class, $cachedResponse);
        self::assertSame('custom-value', $cachedResponse->getHeaderLine('X-Custom-Header'));

        @unlink($this->getCachePathForRequest($request));
    }

    public function testServerErrorResponseIsNotCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/error-response-test'));
        $response = Response::create('Server Error', StatusCode::INTERNAL_SERVER_ERROR);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        // Server error responses should not be returned from cache
        self::assertInstanceOf(RequestContract::class, $cachedResponse);

        @unlink($this->getCachePathForRequest($request));
    }

    public function testBadGatewayResponseIsNotCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/bad-gateway-test'));
        $response = Response::create('Bad Gateway', StatusCode::BAD_GATEWAY);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(RequestContract::class, $cachedResponse);

        @unlink($this->getCachePathForRequest($request));
    }

    public function testServiceUnavailableResponseIsNotCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/service-unavailable-test'));
        $response = Response::create('Service Unavailable', StatusCode::SERVICE_UNAVAILABLE);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(RequestContract::class, $cachedResponse);

        @unlink($this->getCachePathForRequest($request));
    }

    public function testGatewayTimeoutResponseIsNotCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/gateway-timeout-test'));
        $response = Response::create('Gateway Timeout', StatusCode::GATEWAY_TIMEOUT);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(RequestContract::class, $cachedResponse);

        @unlink($this->getCachePathForRequest($request));
    }

    public function testNotImplementedResponseIsNotCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/not-implemented-test'));
        $response = Response::create('Not Implemented', StatusCode::NOT_IMPLEMENTED);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        self::assertInstanceOf(RequestContract::class, $cachedResponse);

        @unlink($this->getCachePathForRequest($request));
    }

    public function test4xxResponsesAreCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/not-found-test'));
        $response = Response::create('Not Found', StatusCode::NOT_FOUND);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        // 4xx responses should be cached
        self::assertInstanceOf(ResponseContract::class, $cachedResponse);
        self::assertSame(StatusCode::NOT_FOUND, $cachedResponse->getStatusCode());

        @unlink($this->getCachePathForRequest($request));
    }

    public function test2xxResponsesAreCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest(uri: new Uri(path: '/created-test'));
        $response = Response::create('Created', StatusCode::CREATED);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        // 2xx responses should be cached
        self::assertInstanceOf(ResponseContract::class, $cachedResponse);
        self::assertSame(StatusCode::CREATED, $cachedResponse->getStatusCode());

        @unlink($this->getCachePathForRequest($request));
    }

    public function test3xxResponsesAreCached(): void
    {
        $middleware        = new CacheResponseMiddleware($this->filePath);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $redirectUri = new Uri(path: '/destination');
        $request     = new ServerRequest(uri: new Uri(path: '/redirect-cached-test'));
        $response    = new RedirectResponse($redirectUri, StatusCode::MOVED_PERMANENTLY);

        $middleware->terminated($request, $response, $terminatedHandler);

        $cachedResponse = $middleware->requestReceived($request, $beforeHandler);

        // 3xx responses should be cached
        self::assertInstanceOf(ResponseContract::class, $cachedResponse);
        self::assertSame(StatusCode::MOVED_PERMANENTLY, $cachedResponse->getStatusCode());

        @unlink($this->getCachePathForRequest($request));
    }

    public function testExceptionCausedByCacheFileShouldReturnRequest(): void
    {
        $middleware    = new CacheResponseMiddleware($this->filePath);
        $beforeHandler = new RequestReceivedHandler();

        $request = new ServerRequest();

        // Write a bad cache
        file_put_contents($this->getCachePathForRequest($request), '<?php throw new \Exception();');

        $beforeResponseWithBadCache = $middleware->requestReceived($request, $beforeHandler);

        // Test that a subsequent request gets a request when the cached response is not valid
        self::assertInstanceOf(RequestContract::class, $beforeResponseWithBadCache);

        @unlink($this->getCachePathForRequest($request));
    }

    /**
     * Get the cache path for a request.
     */
    protected function getCachePathForRequest(ServerRequest $request): string
    {
        return $this->filePath . '/' . md5($request->getUri()->getPath() . $request->getMethod()->value);
    }
}
