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

namespace Valkyrja\Tests\Unit\Container\Provider;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Config\Config\Config;
use Valkyrja\Config\Config\Valkyrja;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Tests\Unit\TestCase;

use function array_map;
use function class_exists;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTestCase extends TestCase
{
    /** @var class-string<Provider> */
    protected static string $provider;
    /** @var class-string<\Valkyrja\Config\Config> */
    protected static string $config;

    protected Container $container;

    public static function publishersDataProvider(): array
    {
        return array_map(static fn ($item) => [$item], static::getPublishers());
    }

    public static function providesDataProvider(): array
    {
        return array_map(static fn ($item) => [$item], static::getProvides());
    }

    protected static function getPublishers(): array
    {
        return static::$provider::publishers();
    }

    protected static function getProvides(): array
    {
        return static::$provider::provides();
    }

    protected static function assertValidProvided(string $provided): void
    {
        if (class_exists($provided)) {
            self::assertClassExists($provided);

            return;
        }

        self::assertInterfaceExists($provided);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();

        $this->container->setSingleton(Config::class, new Valkyrja(null, true));
    }

    /**
     * @param class-string $provided
     */
    #[DataProvider('providesDataProvider')]
    public function testProvides(string $provided): void
    {
        self::assertValidProvided($provided);
    }

    #[DataProvider('publishersDataProvider')]
    public function testPublishers(array $callable): void
    {
        self::assertIsCallable($callable);
    }
}
