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

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Env\Env;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\Manager\NullFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;
use Valkyrja\Filesystem\Provider\ServiceProvider;
use Valkyrja\Tests\Classes\Filesystem\EnvClass;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        $this->container->setSingleton(FlysystemFilesystem::class, self::createStub(FlysystemFilesystem::class));

        ServiceProvider::publishFilesystem($this->container);

        self::assertInstanceOf(FlysystemFilesystem::class, $this->container->getSingleton(FilesystemContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishFlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        $this->container->setSingleton(LocalFlysystemFilesystem::class, self::createStub(LocalFlysystemFilesystem::class));

        ServiceProvider::publishFlysystemFilesystem($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(FlysystemFilesystem::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLocalFlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        $this->container->setSingleton(LocalFilesystemAdapter::class, self::createStub(LocalFilesystemAdapter::class));

        ServiceProvider::publishLocalFlysystemFilesystem($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(LocalFlysystemFilesystem::class));
    }

    public function testPublishFlysystemLocalAdapter(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        ServiceProvider::publishFlysystemLocalAdapter($this->container);

        self::assertInstanceOf(LocalFilesystemAdapter::class, $this->container->getSingleton(LocalFilesystemAdapter::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishS3FlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        $this->container->setSingleton(AwsS3V3Adapter::class, self::createStub(AwsS3V3Adapter::class));

        ServiceProvider::publishS3FlysystemFilesystem($this->container);

        self::assertInstanceOf(S3FlysystemFilesystem::class, $this->container->getSingleton(S3FlysystemFilesystem::class));
    }

    public function testPublishFlysystemAwsS3Adapter(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        ServiceProvider::publishFlysystemAwsS3Adapter($this->container);

        self::assertInstanceOf(AwsS3V3Adapter::class, $this->container->getSingleton(AwsS3V3Adapter::class));
    }

    public function testPublishInMemoryFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        ServiceProvider::publishInMemoryFilesystem($this->container);

        self::assertInstanceOf(InMemoryFilesystem::class, $this->container->getSingleton(InMemoryFilesystem::class));
    }

    public function testPublishNullFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new EnvClass());
        ServiceProvider::publishNullFilesystem($this->container);

        self::assertInstanceOf(NullFilesystem::class, $this->container->getSingleton(NullFilesystem::class));
    }
}
