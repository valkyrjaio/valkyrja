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

namespace Valkyrja\Tests\Functional\Application\Entry\FrankenPhp;

use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Application\Entry\FrankenPhp\FrankenPhpHttpSmokeFixture;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the FrankenPhpHttp entry point against a fully booted application.
 */
#[RunTestsInSeparateProcesses]
final class FrankenPhpHttpTest extends TestCase
{
    private ApplicationContract $workerApp;

    /**
     * The route handler.
     */
    public static function handleRoute(): TextResponse
    {
        return new TextResponse('FrankenPhp route');
    }

    #[Override]
    protected function setUp(): void
    {
        $this->workerApp = FrankenPhpHttp::bootstrap(new HttpConfig(dir: Directory::$basePath));

        $collection = $this->workerApp->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/franken',
                name: 'franken',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    public function testRunLoopDispatchesRouteAndEmitsThroughSapi(): void
    {
        FrankenPhpHttpSmokeFixture::reset();

        FrankenPhpHttpSmokeFixture::$app        = $this->workerApp;
        FrankenPhpHttpSmokeFixture::$requestUri = '/franken';

        FrankenPhpHttpSmokeFixture::run(new HttpConfig());

        self::assertStringContainsString('FrankenPhp route', (string) FrankenPhpHttpSmokeFixture::$sentBody);
    }
}
