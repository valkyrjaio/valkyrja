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

namespace Valkyrja\Tests\Unit\Application\Entry\OpenSwoole;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Http\Server;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Tests\Fixtures\Application\Entry\OpenSwoole\OpenSwooleHttpFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_column;
use function extension_loaded;

/**
 * Test the OpenSwooleHttp entry point.
 *
 * OpenSwoole permits a single server per process, so each test runs in an
 * isolated process.
 */
#[RunTestsInSeparateProcesses]
final class OpenSwooleHttpTest extends TestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        OpenSwooleHttpFixture::reset();

        $container = self::createStub(ContainerContract::class);
        $container->method('getData')->willReturn(new ContainerData());

        $app = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);

        OpenSwooleHttpFixture::$app = $app;
    }

    public function testRunBuildsServerRegistersHandlersAndStarts(): void
    {
        OpenSwooleHttpFixture::run(new HttpConfig());

        self::assertTrue(OpenSwooleHttpFixture::$serverStarted);
    }

    public function testGetSwooleServerReturnsServer(): void
    {
        self::assertInstanceOf(Server::class, OpenSwooleHttp::getSwooleServer());
    }

    public function testGetRequestHandlerConvertsHandlesAndEmits(): void
    {
        OpenSwooleHttpFixture::$frameworkResponse = new TextResponse('Body', StatusCode::ACCEPTED);

        $handler = OpenSwooleHttpFixture::getRequestHandler(OpenSwooleHttpFixture::$app, new ContainerData());

        $handler(new Request(), new Response());

        self::assertSame(1, OpenSwooleHttpFixture::$handleSwooleRequestCallCount);
        self::assertSame(StatusCode::ACCEPTED->value, OpenSwooleHttpFixture::$sentStatus['statusCode'] ?? null);
        self::assertSame('Body', OpenSwooleHttpFixture::$sentBody);
    }

    public function testGetRequestFromSwooleRequestBuildsServerRequest(): void
    {
        OpenSwooleHttpFixture::$rawContent = 'payload';

        $swooleRequest         = new Request();
        $swooleRequest->server = [
            'request_method'  => 'POST',
            'request_uri'     => '/users',
            'query_string'    => 'page=2',
            'server_protocol' => 'HTTP/1.1',
        ];
        $swooleRequest->header = [
            'host'           => 'example.com',
            'content-type'   => 'application/json',
            'content-length' => '7',
            'x-custom'       => 'val',
        ];
        $swooleRequest->get    = ['page' => '2'];
        $swooleRequest->post   = ['name' => 'Jane'];
        $swooleRequest->cookie = ['session' => 'abc'];

        $request = OpenSwooleHttpFixture::getRequestFromSwooleRequest($swooleRequest);

        self::assertInstanceOf(ServerRequestContract::class, $request);
        self::assertSame('payload', (string) $request->getBody());
        self::assertSame(RequestMethod::POST, $request->getMethod());
        self::assertSame('/users', $request->getUri()->getPath());
        self::assertSame('application/json', $request->getHeaders()->getHeaderLine('content-type'));
        self::assertSame('val', $request->getHeaders()->getHeaderLine('x-custom'));
        self::assertSame('example.com', $request->getHeaders()->getHeaderLine('host'));
    }

    public function testEmitSwooleResponseWritesStatusHeadersAndBody(): void
    {
        $response = new TextResponse('Hello Swoole', StatusCode::ACCEPTED);

        OpenSwooleHttpFixture::emitSwooleResponse($response, new Response());

        self::assertSame(StatusCode::ACCEPTED->value, OpenSwooleHttpFixture::$sentStatus['statusCode'] ?? null);
        self::assertSame($response->getReasonPhrase(), OpenSwooleHttpFixture::$sentStatus['reasonPhrase'] ?? null);
        self::assertSame('Hello Swoole', OpenSwooleHttpFixture::$sentBody);
        self::assertNotSame([], OpenSwooleHttpFixture::$sentHeaders);

        $headerNames = array_column(OpenSwooleHttpFixture::$sentHeaders, 'name');

        self::assertContains('Content-Type', $headerNames);
    }

    public function testOnStartDoesNotThrow(): void
    {
        $this->expectNotToPerformAssertions();

        OpenSwooleHttp::onStart(OpenSwooleHttp::getSwooleServer());
    }
}
