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
use Valkyrja\Filesystem\Provider\FilesystemServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = FilesystemServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(FilesystemContract::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(FlysystemFilesystem::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(LocalFlysystemFilesystem::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(LocalFilesystemAdapter::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(S3FlysystemFilesystem::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(AwsS3V3Adapter::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(InMemoryFilesystem::class, FilesystemServiceProvider::publishers());
        self::assertArrayHasKey(NullFilesystem::class, FilesystemServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());
        $this->container->setSingleton(FlysystemFilesystem::class, self::createStub(FlysystemFilesystem::class));

        $callback = FilesystemServiceProvider::publishers()[FilesystemContract::class];
        $callback($this->container);

        self::assertInstanceOf(FlysystemFilesystem::class, $this->container->getSingleton(FilesystemContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishFlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());
        $this->container->setSingleton(LocalFlysystemFilesystem::class, self::createStub(LocalFlysystemFilesystem::class));

        $callback = FilesystemServiceProvider::publishers()[FlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(FlysystemFilesystem::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLocalFlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());
        $this->container->setSingleton(LocalFilesystemAdapter::class, self::createStub(LocalFilesystemAdapter::class));

        $callback = FilesystemServiceProvider::publishers()[LocalFlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(LocalFlysystemFilesystem::class));
    }

    public function testPublishFlysystemLocalAdapter(): void
    {
        $this->container->setSingleton(Env::class, new Env());

        $callback = FilesystemServiceProvider::publishers()[LocalFilesystemAdapter::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFilesystemAdapter::class, $this->container->getSingleton(LocalFilesystemAdapter::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishS3FlysystemFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());
        $this->container->setSingleton(AwsS3V3Adapter::class, self::createStub(AwsS3V3Adapter::class));

        $callback = FilesystemServiceProvider::publishers()[S3FlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(S3FlysystemFilesystem::class, $this->container->getSingleton(S3FlysystemFilesystem::class));
    }

    public function testPublishFlysystemAwsS3Adapter(): void
    {
        $this->container->setSingleton(Env::class, new Env());

        $callback = FilesystemServiceProvider::publishers()[AwsS3V3Adapter::class];
        $callback($this->container);

        self::assertInstanceOf(AwsS3V3Adapter::class, $this->container->getSingleton(AwsS3V3Adapter::class));
    }

    public function testPublishInMemoryFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());

        $callback = FilesystemServiceProvider::publishers()[InMemoryFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(InMemoryFilesystem::class, $this->container->getSingleton(InMemoryFilesystem::class));
    }

    public function testPublishNullFilesystem(): void
    {
        $this->container->setSingleton(Env::class, new Env());

        $callback = FilesystemServiceProvider::publishers()[NullFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(NullFilesystem::class, $this->container->getSingleton(NullFilesystem::class));
    }
}
