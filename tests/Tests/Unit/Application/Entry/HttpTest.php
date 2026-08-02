<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Application\Entry;

use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use TypeError;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Http service.
 */
#[RunTestsInSeparateProcesses]
final class HttpTest extends TestCase
{
    public function testRunThrowsWhenBaseConfigPassed(): void
    {
        $this->expectException(TypeError::class);

        Http::run(config: new Config());
    }

    public function testRunBootstrapsAndDispatchesRequestHandler(): void
    {
        $handler = $this->createMock(RequestHandlerContract::class);
        $handler->expects($this->once())
            ->method('run')
            ->with(self::isInstanceOf(ServerRequestContract::class));

        $container = $this->createMock(ContainerContract::class);
        $container->expects($this->once())
            ->method('getSingleton')
            ->with(RequestHandlerContract::class)
            ->willReturn($handler);

        $app = $this->createMock(ApplicationContract::class);
        $app->expects($this->once())->method('getContainer')->willReturn($container);
        $app->expects($this->once())->method('getDebugMode')->willReturn(false);

        $entry = new class extends Http {
            public static ApplicationContract $appMock;

            #[Override]
            public static function start(ConfigContract $config): ApplicationContract
            {
                return self::$appMock;
            }
        };
        $entry::$appMock = $app;

        $entry::run(new HttpConfig());
    }
}
