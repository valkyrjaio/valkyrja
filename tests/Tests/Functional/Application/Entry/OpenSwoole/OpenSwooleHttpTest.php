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

namespace Valkyrja\Tests\Functional\Application\Entry\OpenSwoole;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Application\Entry\OpenSwoole\OpenSwooleHttpSmokeFixture;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function extension_loaded;

/**
 * Test the OpenSwooleHttp entry point against a fully booted application.
 */
#[RunTestsInSeparateProcesses]
final class OpenSwooleHttpTest extends TestCase
{
    private ApplicationContract $workerApp;

    /**
     * The route handler.
     */
    public static function handleRoute(): TextResponse
    {
        return new TextResponse('Swoole route');
    }

    #[Override]
    protected function setUp(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        $this->workerApp = OpenSwooleHttp::bootstrap(new HttpConfig(dir: Directory::$basePath));

        $collection = $this->workerApp->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/swoole',
                name: 'swoole',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    public function testHandleSwooleRequestDispatchesAndReturnsResponse(): void
    {
        $data = $this->workerApp->getContainer()->getData();

        $request = RequestFactory::fromGlobals(
            server: [
                'REQUEST_URI'    => '/swoole',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $response = OpenSwooleHttp::handleSwooleRequest($this->workerApp, $data, $request);

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame('Swoole route', (string) $response->getBody());
    }

    public function testOnRequestConvertsDispatchesAndEmitsRouteResponse(): void
    {
        OpenSwooleHttpSmokeFixture::reset();

        $data = $this->workerApp->getContainer()->getData();

        $swooleRequest         = new Request();
        $swooleRequest->server = [
            'request_uri'    => '/swoole',
            'request_method' => 'GET',
        ];

        OpenSwooleHttpSmokeFixture::onRequest($this->workerApp, $data, $swooleRequest, new Response());

        self::assertSame(StatusCode::OK->value, OpenSwooleHttpSmokeFixture::$sentStatus);
        self::assertSame('Swoole route', OpenSwooleHttpSmokeFixture::$sentBody);
    }
}
