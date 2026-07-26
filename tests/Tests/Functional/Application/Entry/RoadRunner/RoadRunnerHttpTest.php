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

namespace Valkyrja\Tests\Functional\Application\Entry\RoadRunner;

use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the RoadRunnerHttp entry point against a fully booted application.
 */
#[RunTestsInSeparateProcesses]
final class RoadRunnerHttpTest extends TestCase
{
    private ApplicationContract $workerApp;

    /**
     * The route handler.
     */
    public static function handleRoute(): TextResponse
    {
        return new TextResponse('RoadRunner route');
    }

    #[Override]
    protected function setUp(): void
    {
        $this->workerApp = RoadRunnerHttp::bootstrap(new HttpConfig(dir: Directory::$basePath));

        $collection = $this->workerApp->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/roadrunner',
                name: 'roadrunner',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    public function testHandleRoadRunnerRequestDispatchesAndReturnsResponse(): void
    {
        $data = $this->workerApp->getContainer()->getData();

        $request = RequestFactory::fromGlobals(
            server: [
                'REQUEST_URI'    => '/roadrunner',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $response = RoadRunnerHttp::handleRoadRunnerRequest($this->workerApp, $data, $request);

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame('RoadRunner route', (string) $response->getBody());
    }
}
