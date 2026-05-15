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

namespace Valkyrja\Tests\Unit\Filesystem\Provider;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Filesystem\Provider\FilesystemComponentProvider;
use Valkyrja\Filesystem\Provider\FilesystemServiceProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
final class ComponentProviderTest extends TestCase
{
    public function testGetComponentProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(new FilesystemComponentProvider()->getComponentProviders($app));
    }

    public function testGetContainerProvider(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertInstanceOf(FilesystemServiceProvider::class, new FilesystemComponentProvider()->getContainerProviders($app)[0]);
    }

    public function testGetEventProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(new FilesystemComponentProvider()->getEventProviders($app));
    }

    public function testGetCliProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(new FilesystemComponentProvider()->getCliProviders($app));
    }

    public function testGetHttpProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(new FilesystemComponentProvider()->getHttpProviders($app));
    }
}
