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
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Collector\Contract\Collector;
use Valkyrja\Container\Config as ContainerConfig;
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
        $config    = new ContainerConfig();
        $container = new CacheableContainer($config);

        $annotator = $this->createMock(Collector::class);
        $annotator->method('getServices')->willReturn([]);
        $annotator->method('getContextServices')->willReturn([]);
        $annotator->method('getAliases')->willReturn([]);

        $container->setSingleton(Collector::class, $annotator);

        $cacheable = $container->getCacheable();

        self::assertInstanceOf(ContainerConfig::class, $cacheable);
    }

    /**
     * @throws Exception
     */
    public function testSetup(): void
    {
        $config    = new ContainerConfig();
        $container = new CacheableContainer($config);

        $annotator = $this->createMock(Collector::class);
        $annotator->method('getServices')->willReturn([]);
        $annotator->method('getContextServices')->willReturn([]);
        $annotator->method('getAliases')->willReturn([]);

        $container->setSingleton(Collector::class, $annotator);

        $cacheable = $container->getCacheable();

        $config->cache = $cacheable->cache;

        $container->setup(true, true);

        self::assertInstanceOf(ContainerConfig::class, $cacheable);
    }
}
