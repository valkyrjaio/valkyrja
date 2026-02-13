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

namespace Valkyrja\Filesystem\Provider;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\Manager\NullFilesystem;
use Valkyrja\Filesystem\Manager\S3FlysystemFilesystem;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            FilesystemContract::class       => [self::class, 'publishFilesystem'],
            FlysystemFilesystem::class      => [self::class, 'publishFlysystemFilesystem'],
            LocalFlysystemFilesystem::class => [self::class, 'publishLocalFlysystemFilesystem'],
            LocalFilesystemAdapter::class   => [self::class, 'publishFlysystemLocalAdapter'],
            S3FlysystemFilesystem::class    => [self::class, 'publishS3FlysystemFilesystem'],
            AwsS3V3Adapter::class           => [self::class, 'publishFlysystemAwsS3Adapter'],
            InMemoryFilesystem::class       => [self::class, 'publishInMemoryFilesystem'],
            NullFilesystem::class           => [self::class, 'publishNullFilesystem'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            FilesystemContract::class,
            FlysystemFilesystem::class,
            LocalFlysystemFilesystem::class,
            LocalFilesystemAdapter::class,
            S3FlysystemFilesystem::class,
            AwsS3V3Adapter::class,
            InMemoryFilesystem::class,
            NullFilesystem::class,
        ];
    }

    /**
     * Publish the filesystem service.
     */
    public static function publishFilesystem(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<FilesystemContract> $default */
        $default = $env::FILESYSTEM_DEFAULT
            ?? FlysystemFilesystem::class;

        $container->setSingleton(
            FilesystemContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the flysystem filesystem service.
     */
    public static function publishFlysystemFilesystem(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<FilesystemContract> $default */
        $default = $env::FLYSYSTEM_FILESYSTEM_DEFAULT
            ?? LocalFlysystemFilesystem::class;

        $container->setSingleton(
            FlysystemFilesystem::class,
            $container->getSingleton($default),
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
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $path */
        $path = $env::FILESYSTEM_FLYSYSTEM_LOCAL_PATH
            ?? '/storage/app';

        $container->setSingleton(
            LocalFilesystemAdapter::class,
            new LocalFilesystemAdapter(
                location: Directory::basePath(path: $path)
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
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $key */
        $key = $env::FILESYSTEM_FLYSYSTEM_S3_KEY
            ?? 's3-key';
        /** @var non-empty-string $secret */
        $secret = $env::FILESYSTEM_FLYSYSTEM_S3_SECRET
            ?? 's3-secret';
        /** @var non-empty-string $region */
        $region = $env::FILESYSTEM_FLYSYSTEM_S3_REGION
            ?? 'us-east-1';
        /** @var non-empty-string $version */
        $version = $env::FILESYSTEM_FLYSYSTEM_S3_VERSION
            ?? 'latest';
        /** @var non-empty-string $bucket */
        $bucket = $env::FILESYSTEM_FLYSYSTEM_S3_BUCKET
            ?? 's3-bucket';
        /** @var string $prefix */
        $prefix = $env::FILESYSTEM_FLYSYSTEM_S3_PREFIX
            ?? '';
        /** @var array<array-key, mixed> $options */
        $options = $env::FILESYSTEM_FLYSYSTEM_S3_OPTIONS
            ?? [];

        $clientConfig = [
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
            'region'      => $region,
            'version'     => $version,
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
}
