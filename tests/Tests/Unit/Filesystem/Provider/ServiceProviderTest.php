<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Filesystem\Provider;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemLocalConfigContract;
use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemS3ConfigContract;
use Valkyrja\Filesystem\Data\FilesystemConfig;
use Valkyrja\Filesystem\Data\FilesystemFlysystemConfig;
use Valkyrja\Filesystem\Data\FilesystemFlysystemLocalConfig;
use Valkyrja\Filesystem\Data\FilesystemFlysystemS3Config;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\Manager\NullFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;
use Valkyrja\Filesystem\Provider\FilesystemServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Filesystem\Data\FilesystemConfigFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = FilesystemServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(FilesystemConfigContract::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(FilesystemFlysystemConfigContract::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(FilesystemFlysystemLocalConfigContract::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(FilesystemFlysystemS3ConfigContract::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(FilesystemContract::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(FlysystemFilesystem::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(LocalFlysystemFilesystem::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(LocalFilesystemAdapter::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(S3FlysystemFilesystem::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(AwsS3V3Adapter::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(InMemoryFilesystem::class, new FilesystemServiceProvider()->publishers());
        self::assertArrayHasKey(NullFilesystem::class, new FilesystemServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishConfig(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[FilesystemConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemConfigContract::class, $config = $this->container->getSingleton(FilesystemConfigContract::class));
        self::assertSame(FlysystemFilesystem::class, $config->defaultFilesystem);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new FilesystemConfigFixture());

        $callback = new FilesystemServiceProvider()->publishers()[FilesystemConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemConfigContract::class, $config = $this->container->getSingleton(FilesystemConfigContract::class));
        self::assertSame(InMemoryFilesystem::class, $config->defaultFilesystem);
    }

    public function testPublishFlysystemConfig(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemConfigContract::class));
        self::assertSame(LocalFlysystemFilesystem::class, $config->defaultFlysystemFilesystem);
    }

    public function testPublishFlysystemConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new FilesystemConfigFixture());

        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemConfigContract::class));
        self::assertSame(S3FlysystemFilesystem::class, $config->defaultFlysystemFilesystem);
    }

    public function testPublishFlysystemLocalConfig(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemLocalConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemLocalConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemLocalConfigContract::class));
        self::assertSame('/storage/app', $config->flysystemLocalPath);
    }

    public function testPublishFlysystemLocalConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new FilesystemConfigFixture());

        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemLocalConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemLocalConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemLocalConfigContract::class));
        self::assertSame('/storage/test', $config->flysystemLocalPath);
    }

    public function testPublishFlysystemS3Config(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemS3ConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemS3ConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemS3ConfigContract::class));
        self::assertSame('s3-bucket', $config->flysystemS3Bucket);
    }

    public function testPublishFlysystemS3ConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new FilesystemConfigFixture());

        $callback = new FilesystemServiceProvider()->publishers()[FilesystemFlysystemS3ConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(FilesystemFlysystemS3ConfigContract::class, $config = $this->container->getSingleton(FilesystemFlysystemS3ConfigContract::class));
        self::assertSame('test-bucket', $config->flysystemS3Bucket);
    }

    public function testPublishFilesystem(): void
    {
        $this->container->setSingleton(FilesystemConfigContract::class, new FilesystemConfig());
        $this->container->setSingleton(FlysystemFilesystem::class, self::createStub(FlysystemFilesystem::class));

        $callback = new FilesystemServiceProvider()->publishers()[FilesystemContract::class];
        $callback($this->container);

        self::assertInstanceOf(FlysystemFilesystem::class, $this->container->getSingleton(FilesystemContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishFlysystemFilesystem(): void
    {
        $this->container->setSingleton(FilesystemFlysystemConfigContract::class, new FilesystemFlysystemConfig());
        $this->container->setSingleton(LocalFlysystemFilesystem::class, self::createStub(LocalFlysystemFilesystem::class));

        $callback = new FilesystemServiceProvider()->publishers()[FlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(FlysystemFilesystem::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLocalFlysystemFilesystem(): void
    {
        $this->container->setSingleton(FilesystemFlysystemLocalConfigContract::class, new FilesystemFlysystemLocalConfig());
        $this->container->setSingleton(LocalFilesystemAdapter::class, self::createStub(LocalFilesystemAdapter::class));

        $callback = new FilesystemServiceProvider()->publishers()[LocalFlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFlysystemFilesystem::class, $this->container->getSingleton(LocalFlysystemFilesystem::class));
    }

    public function testPublishFlysystemLocalAdapter(): void
    {
        $this->container->setSingleton(FilesystemFlysystemLocalConfigContract::class, new FilesystemFlysystemLocalConfig());

        $callback = new FilesystemServiceProvider()->publishers()[LocalFilesystemAdapter::class];
        $callback($this->container);

        self::assertInstanceOf(LocalFilesystemAdapter::class, $this->container->getSingleton(LocalFilesystemAdapter::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishS3FlysystemFilesystem(): void
    {
        $this->container->setSingleton(FilesystemFlysystemS3ConfigContract::class, new FilesystemFlysystemS3Config());
        $this->container->setSingleton(AwsS3V3Adapter::class, self::createStub(AwsS3V3Adapter::class));

        $callback = new FilesystemServiceProvider()->publishers()[S3FlysystemFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(S3FlysystemFilesystem::class, $this->container->getSingleton(S3FlysystemFilesystem::class));
    }

    public function testPublishFlysystemAwsS3Adapter(): void
    {
        $this->container->setSingleton(FilesystemFlysystemS3ConfigContract::class, new FilesystemFlysystemS3Config());

        $callback = new FilesystemServiceProvider()->publishers()[AwsS3V3Adapter::class];
        $callback($this->container);

        self::assertInstanceOf(AwsS3V3Adapter::class, $this->container->getSingleton(AwsS3V3Adapter::class));
    }

    public function testPublishInMemoryFilesystem(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[InMemoryFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(InMemoryFilesystem::class, $this->container->getSingleton(InMemoryFilesystem::class));
    }

    public function testPublishNullFilesystem(): void
    {
        $callback = new FilesystemServiceProvider()->publishers()[NullFilesystem::class];
        $callback($this->container);

        self::assertInstanceOf(NullFilesystem::class, $this->container->getSingleton(NullFilesystem::class));
    }
}
