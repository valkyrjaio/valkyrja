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

use League\Flysystem\AwsS3V3\AwsS3V3Adapter as FlysystemAwsS3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter as FlysystemLocalAdapter;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Filesystem\Contract\Filesystem as Contract;
use Valkyrja\Filesystem\FlysystemFilesystem;
use Valkyrja\Filesystem\InMemoryFilesystem;
use Valkyrja\Filesystem\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\NullFilesystem;
use Valkyrja\Filesystem\Provider\ServiceProvider;
use Valkyrja\Filesystem\S3FlysystemFilesystem;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
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
        $this->container->setSingleton(FlysystemFilesystem::class, $this->createMock(FlysystemFilesystem::class));

        ServiceProvider::publishFilesystem($this->container);

        self::assertInstanceOf(FlysystemFilesystem::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishFlysystemFilesystem(): void
    {
        $this->container->setSingleton(LocalFlysystemFilesystem::class, $this->createMock(LocalFlysystemFilesystem::class));

        ServiceProvider::publishFlysystemFilesystem($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(FlysystemFilesystem::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLocalFlysystemFilesystem(): void
    {
        $this->container->setSingleton(FlysystemLocalAdapter::class, $this->createMock(FlysystemLocalAdapter::class));

        ServiceProvider::publishLocalFlysystemFilesystem($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(LocalFlysystemFilesystem::class));
    }

    public function testPublishFlysystemLocalAdapter(): void
    {
        ServiceProvider::publishFlysystemLocalAdapter($this->container);

        self::assertInstanceOf(FlysystemLocalAdapter::class, $this->container->getSingleton(FlysystemLocalAdapter::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishS3FlysystemFilesystem(): void
    {
        $this->container->setSingleton(FlysystemAwsS3Adapter::class, $this->createMock(FlysystemAwsS3Adapter::class));

        ServiceProvider::publishS3FlysystemFilesystem($this->container);

        self::assertInstanceOf(S3FlysystemFilesystem::class, $this->container->getSingleton(S3FlysystemFilesystem::class));
    }

    public function testPublishFlysystemAwsS3Adapter(): void
    {
        ServiceProvider::publishFlysystemAwsS3Adapter($this->container);

        self::assertInstanceOf(FlysystemAwsS3Adapter::class, $this->container->getSingleton(FlysystemAwsS3Adapter::class));
    }

    public function testPublishInMemoryFilesystem(): void
    {
        ServiceProvider::publishInMemoryFilesystem($this->container);

        self::assertInstanceOf(InMemoryFilesystem::class, $this->container->getSingleton(InMemoryFilesystem::class));
    }

    public function testPublishNullFilesystem(): void
    {
        ServiceProvider::publishNullFilesystem($this->container);

        self::assertInstanceOf(NullFilesystem::class, $this->container->getSingleton(NullFilesystem::class));
    }
}
