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

namespace Valkyrja\Tests\Unit\Http\Middleware\Cache;

use Valkyrja\Container\Container;
use Valkyrja\Filesystem\Adapter\InMemoryAdapter;
use Valkyrja\Filesystem\Config;
use Valkyrja\Filesystem\Config\Configurations;
use Valkyrja\Filesystem\Config\InMemoryConfiguration;
use Valkyrja\Filesystem\Driver\Driver;
use Valkyrja\Filesystem\Factory\ContainerFactory;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Time;
use Valkyrja\Tests\Unit\TestCase;

use function md5;

class CacheResponseMiddlewareTest extends TestCase
{
    public function testThroughHandler(): void
    {
        $container  = new Container();
        $filesystem = $this->getFilesystem($container);

        $container->setSingleton(\Valkyrja\Filesystem\Contract\Filesystem::class, $filesystem);
        $container->setCallable(CacheResponseMiddleware::class, static fn () => new CacheResponseMiddleware($filesystem));

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);
        $terminatedHandler = new TerminatedHandler($container);
        $terminatedHandler->add(CacheResponseMiddleware::class);

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $beforeResponse = $beforeHandler->requestReceived($request);

        $terminatedHandler->terminated($request, $response);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(Request::class, $beforeResponse);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTerminated = $beforeHandler->requestReceived($request);

        // Test that a subsequent request gets a response from cache
        self::assertInstanceOf(Response::class, $beforeResponseAfterTerminated);

        // Write a bad cache
        $filesystem->write($this->getCachePathForRequest($request), 'bad-cache-test');

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseWithBadCache = $beforeHandler->requestReceived($request);

        // Test that a subsequent request gets a request when the cached response is not valid
        self::assertInstanceOf(Request::class, $beforeResponseWithBadCache);

        $terminatedHandler = new TerminatedHandler($container);
        $terminatedHandler->add(CacheResponseMiddleware::class);

        $terminatedHandler->terminated($request, $response);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTerminatedAfterBadCache = $beforeHandler->requestReceived($request);

        // Test that cache got reset successfully
        self::assertInstanceOf(Response::class, $beforeResponseAfterTerminatedAfterBadCache);

        // Freeze time to a time much later than ttl
        Time::freeze(Time::get() + 1801);

        $beforeHandler = new RequestReceivedHandler($container);
        $beforeHandler->add(CacheResponseMiddleware::class);

        $beforeResponseAfterTtlExpired = $beforeHandler->requestReceived($request);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(Request::class, $beforeResponseAfterTtlExpired);

        // Unfreeze for future tests
        Time::unfreeze();

        $filesystem->deleteDir(Directory::cachePath('response/'));
    }

    public function testDirectly(): void
    {
        $container         = new Container();
        $filesystem        = $this->getFilesystem($container);
        $middleware        = new CacheResponseMiddleware($filesystem);
        $beforeHandler     = new RequestReceivedHandler();
        $terminatedHandler = new TerminatedHandler();

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $beforeResponse = $middleware->requestReceived($request, $beforeHandler);

        $middleware->terminated($request, $response, $terminatedHandler);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(Request::class, $beforeResponse);

        $beforeHandler = new RequestReceivedHandler();

        $beforeResponseAfterTerminated = $middleware->requestReceived($request, $beforeHandler);

        // Test that a subsequent request gets a response from cache
        self::assertInstanceOf(Response::class, $beforeResponseAfterTerminated);

        // Write a bad cache
        $filesystem->write($this->getCachePathForRequest($request), 'bad-cache-test');

        $beforeHandler = new RequestReceivedHandler();

        $beforeResponseWithBadCache = $middleware->requestReceived($request, $beforeHandler);

        // Test that a subsequent request gets a request when the cached response is not valid
        self::assertInstanceOf(Request::class, $beforeResponseWithBadCache);

        $middleware->terminated($request, $response, $terminatedHandler);

        $beforeResponseAfterTerminatedAfterBadCache = $middleware->requestReceived($request, $beforeHandler);

        // Test that cache got reset successfully
        self::assertInstanceOf(Response::class, $beforeResponseAfterTerminatedAfterBadCache);

        // Freeze time to a time much later than ttl
        Time::freeze(Time::get() + 1801);

        $beforeResponseAfterTtlExpired = $middleware->requestReceived($request, $beforeHandler);

        // Ensure the initial request doesn't get any cached response
        self::assertInstanceOf(Request::class, $beforeResponseAfterTtlExpired);

        // Unfreeze for future tests
        Time::unfreeze();

        $filesystem->deleteDir(Directory::cachePath('response/'));
    }

    protected function getFilesystem(Container $container): Filesystem
    {
        $container->setSingleton(InMemoryAdapter::class, $adapter = new InMemoryAdapter());
        $container->setSingleton(Driver::class, new Driver($adapter));

        return new Filesystem(
            new ContainerFactory($container),
            config: new Config(
                defaultConfiguration: 'memory',
                configurations: new Configurations(
                    memory: new InMemoryConfiguration()
                )
            )
        );
    }

    /**
     * Get the cache path for a request.
     */
    protected function getCachePathForRequest(ServerRequest $request): string
    {
        return Directory::cachePath('response/' . md5($request->getUri()->getPath()));
    }
}
