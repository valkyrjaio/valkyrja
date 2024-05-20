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

namespace Valkyrja\Tests\Unit\Container;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Container\Annotator;
use Valkyrja\Container\Config\Config;
use Valkyrja\Container\Config\Container as ContainerConfig;
use Valkyrja\Container\Constants\Provider;
use Valkyrja\Container\Managers\CacheableContainer;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the CacheableContainer service.
 *
 * @author Melech Mizrachi
 */
class CacheableContainerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetCacheable(): void
    {
        $config    = new ContainerConfig(setup: true);
        $container = new CacheableContainer($config, true);

        $annotator = $this->createMock(Annotator::class);
        $annotator->method('getServices')->willReturn([]);
        $annotator->method('getContextServices')->willReturn([]);
        $annotator->method('getAliasServices')->willReturn([]);

        $container->setSingleton(Annotator::class, $annotator);

        $config->devProviders = [
            Provider::VIEW,
        ];

        $cacheable = $container->getCacheable();

        self::assertInstanceOf(Config::class, $cacheable);
    }

    /**
     * @throws Exception
     */
    public function testSetup(): void
    {
        $config    = new ContainerConfig(setup: true);
        $container = new CacheableContainer($config, true);

        $annotator = $this->createMock(Annotator::class);
        $annotator->method('getServices')->willReturn([]);
        $annotator->method('getContextServices')->willReturn([]);
        $annotator->method('getAliasServices')->willReturn([]);

        $container->setSingleton(Annotator::class, $annotator);

        $cacheable = $container->getCacheable();

        $config->useCache = true;
        $config->cache    = $cacheable->cache;

        $container->setup(true, true);

        self::assertInstanceOf(Config::class, $cacheable);
    }
}
