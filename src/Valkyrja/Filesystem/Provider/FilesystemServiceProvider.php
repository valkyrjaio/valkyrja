<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Filesystem\Provider;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Override;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
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

class FilesystemServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the filesystem config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof FilesystemConfigContract) {
            $container->setSingleton(FilesystemConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            FilesystemConfigContract::class,
            new FilesystemConfig()
        );
    }

    /**
     * Publish the flysystem filesystem config service.
     */
    public static function publishFlysystemConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof FilesystemFlysystemConfigContract) {
            $container->setSingleton(FilesystemFlysystemConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            FilesystemFlysystemConfigContract::class,
            new FilesystemFlysystemConfig()
        );
    }

    /**
     * Publish the local flysystem filesystem config service.
     */
    public static function publishFlysystemLocalConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof FilesystemFlysystemLocalConfigContract) {
            $container->setSingleton(FilesystemFlysystemLocalConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            FilesystemFlysystemLocalConfigContract::class,
            new FilesystemFlysystemLocalConfig()
        );
    }

    /**
     * Publish the s3 flysystem filesystem config service.
     */
    public static function publishFlysystemS3Config(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof FilesystemFlysystemS3ConfigContract) {
            $container->setSingleton(FilesystemFlysystemS3ConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            FilesystemFlysystemS3ConfigContract::class,
            new FilesystemFlysystemS3Config()
        );
    }

    /**
     * Publish the filesystem service.
     */
    public static function publishFilesystem(ContainerContract $container): void
    {
        $config = $container->getSingleton(FilesystemConfigContract::class);

        $container->setSingleton(
            FilesystemContract::class,
            $container->getSingleton($config->defaultFilesystem),
        );
    }

    /**
     * Publish the flysystem filesystem service.
     */
    public static function publishFlysystemFilesystem(ContainerContract $container): void
    {
        $config = $container->getSingleton(FilesystemFlysystemConfigContract::class);

        $container->setSingleton(
            FlysystemFilesystem::class,
            $container->getSingleton($config->defaultFlysystemFilesystem),
        );
    }

    /**
     * Publish the local flysystem filesystem service.
     */
    public static function publishLocalFlysystemFilesystem(ContainerContract $container): void
    {
        $container->setSingleton(
            LocalFlysystemFilesystem::class,
            new LocalFlysystemFilesystem(
                new Filesystem(
                    $container->getSingleton(LocalFilesystemAdapter::class),
                )
            ),
        );
    }

    /**
     * Publish the flysystem local adapter service.
     */
    public static function publishFlysystemLocalAdapter(ContainerContract $container): void
    {
        $config = $container->getSingleton(FilesystemFlysystemLocalConfigContract::class);

        $container->setSingleton(
            LocalFilesystemAdapter::class,
            new LocalFilesystemAdapter(
                location: Directory::basePath(path: $config->flysystemLocalPath)
            )
        );
    }

    /**
     * Publish the s3 flysystem filesystem service.
     */
    public static function publishS3FlysystemFilesystem(ContainerContract $container): void
    {
        $container->setSingleton(
            S3FlysystemFilesystem::class,
            new S3FlysystemFilesystem(
                new Filesystem(
                    $container->getSingleton(AwsS3V3Adapter::class),
                )
            ),
        );
    }

    /**
     * Publish the flysystem s3 adapter service.
     */
    public static function publishFlysystemAwsS3Adapter(ContainerContract $container): void
    {
        $config = $container->getSingleton(FilesystemFlysystemS3ConfigContract::class);

        $bucket  = $config->flysystemS3Bucket;
        $prefix  = $config->flysystemS3Prefix;
        $options = $config->flysystemS3Options;

        $clientConfig = [
            'credentials' => [
                'key'    => $config->flysystemS3Key,
                'secret' => $config->flysystemS3Secret,
            ],
            'region'      => $config->flysystemS3Region,
            'version'     => $config->flysystemS3Version,
        ];

        $container->setSingleton(
            AwsS3V3Adapter::class,
            new AwsS3V3Adapter(
                client: new S3Client($clientConfig),
                bucket: $bucket,
                prefix: $prefix,
                options: $options
            ),
        );
    }

    /**
     * Publish the in memory filesystem service.
     */
    public static function publishInMemoryFilesystem(ContainerContract $container): void
    {
        $container->setSingleton(
            InMemoryFilesystem::class,
            new InMemoryFilesystem(),
        );
    }

    /**
     * Publish the null filesystem service.
     */
    public static function publishNullFilesystem(ContainerContract $container): void
    {
        $container->setSingleton(
            NullFilesystem::class,
            new NullFilesystem(),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            FilesystemConfigContract::class               => [self::class, 'publishConfig'],
            FilesystemFlysystemConfigContract::class      => [self::class, 'publishFlysystemConfig'],
            FilesystemFlysystemLocalConfigContract::class => [self::class, 'publishFlysystemLocalConfig'],
            FilesystemFlysystemS3ConfigContract::class    => [self::class, 'publishFlysystemS3Config'],
            FilesystemContract::class                     => [self::class, 'publishFilesystem'],
            FlysystemFilesystem::class                    => [self::class, 'publishFlysystemFilesystem'],
            LocalFlysystemFilesystem::class               => [self::class, 'publishLocalFlysystemFilesystem'],
            LocalFilesystemAdapter::class                 => [self::class, 'publishFlysystemLocalAdapter'],
            S3FlysystemFilesystem::class                  => [self::class, 'publishS3FlysystemFilesystem'],
            AwsS3V3Adapter::class                         => [self::class, 'publishFlysystemAwsS3Adapter'],
            InMemoryFilesystem::class                     => [self::class, 'publishInMemoryFilesystem'],
            NullFilesystem::class                         => [self::class, 'publishNullFilesystem'],
        ];
    }
}
