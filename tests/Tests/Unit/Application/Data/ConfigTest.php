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

namespace Valkyrja\Tests\Unit\Application\Data;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\Config;
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
 * Test the Config service.
 */
final class ConfigTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Config();

        self::assertSame('production', $data->environment);
        self::assertSame(ApplicationInfo::VERSION, $data->version);
        self::assertFalse($data->debugMode);
        self::assertNotEmpty($data->providers);
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
            $data->providers
        );
        self::assertSame('UTC', $data->timezone);
    }
}
