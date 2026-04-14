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

namespace Valkyrja\Tests\Unit\Application\Provider;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\ApplicationComponentProvider;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ViewComponentProvider;

/**
 * Test the Component service.
 */
final class ApplicationComponentProviderTest extends TestCase
{
    public function testGetComponentProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertSame(
            [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                CliInteractionComponentProvider::class,
                CliMiddlewareComponentProvider::class,
                CliRoutingComponentProvider::class,
                CliServerComponentProvider::class,
                EventComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpRoutingCliComponentProvider::class,
                HttpServerComponentProvider::class,
                LogComponentProvider::class,
                ViewComponentProvider::class,
            ],
            ApplicationComponentProvider::getComponentProviders($app)
        );
    }

    public function testGetContainerProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ApplicationComponentProvider::getContainerProviders($app));
    }

    public function testGetEventProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ApplicationComponentProvider::getEventProviders($app));
    }

    public function testGetCliProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ApplicationComponentProvider::getCliProviders($app));
    }

    public function testGetHttpProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ApplicationComponentProvider::getHttpProviders($app));
    }
}
