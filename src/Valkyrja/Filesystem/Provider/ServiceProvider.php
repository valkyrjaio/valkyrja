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

use Aws\S3\S3Client as AwsS3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter as FlysystemAwsS3Adapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Local\LocalFilesystemAdapter as FlysystemLocalAdapter;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\FlysystemFilesystem;
use Valkyrja\Filesystem\InMemoryFilesystem;
use Valkyrja\Filesystem\LocalFlysystemFilesystem;
use Valkyrja\Filesystem\NullFilesystem;
use Valkyrja\Filesystem\S3FlysystemFilesystem;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Filesystem::class               => [self::class, 'publishFilesystem'],
            FlysystemFilesystem::class      => [self::class, 'publishFlysystemFilesystem'],
            LocalFlysystemFilesystem::class => [self::class, 'publishLocalFlysystemFilesystem'],
            FlysystemLocalAdapter::class    => [self::class, 'publishFlysystemLocalAdapter'],
            S3FlysystemFilesystem::class    => [self::class, 'publishS3FlysystemFilesystem'],
            FlysystemAwsS3Adapter::class    => [self::class, 'publishFlysystemAwsS3Adapter'],
            InMemoryFilesystem::class       => [self::class, 'publishInMemoryFilesystem'],
            NullFilesystem::class           => [self::class, 'publishNullFilesystem'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Filesystem::class,
            FlysystemFilesystem::class,
            LocalFlysystemFilesystem::class,
            FlysystemLocalAdapter::class,
            S3FlysystemFilesystem::class,
            FlysystemAwsS3Adapter::class,
            InMemoryFilesystem::class,
            NullFilesystem::class,
        ];
    }

    /**
     * Publish the filesystem service.
     */
    public static function publishFilesystem(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Filesystem> $default */
        $default = $env::FILESYSTEM_DEFAULT;

        $container->setSingleton(
            Filesystem::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the flysystem filesystem service.
     */
    public static function publishFlysystemFilesystem(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Filesystem> $default */
        $default = $env::FLYSYSTEM_FILESYSTEM_DEFAULT;

        $container->setSingleton(
            FlysystemFilesystem::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the local flysystem filesystem service.
     */
    public static function publishLocalFlysystemFilesystem(Container $container): void
    {
        $container->setSingleton(
            LocalFlysystemFilesystem::class,
            new LocalFlysystemFilesystem(
                new Flysystem(
                    $container->getSingleton(FlysystemLocalAdapter::class),
                )
            ),
        );
    }

    /**
     * Publish the flysystem local adapter service.
     */
    public static function publishFlysystemLocalAdapter(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $dir */
        $dir = $env::FILESYSTEM_FLYSYSTEM_LOCAL_DIR;

        $container->setSingleton(
            FlysystemLocalAdapter::class,
            new FlysystemLocalAdapter(
                $dir
            )
        );
    }

    /**
     * Publish the s3 flysystem filesystem service.
     */
    public static function publishS3FlysystemFilesystem(Container $container): void
    {
        $container->setSingleton(
            S3FlysystemFilesystem::class,
            new S3FlysystemFilesystem(
                new Flysystem(
                    $container->getSingleton(FlysystemAwsS3Adapter::class),
                )
            ),
        );
    }

    /**
     * Publish the flysystem s3 adapter service.
     */
    public static function publishFlysystemAwsS3Adapter(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $key */
        $key = $env::FILESYSTEM_FLYSYSTEM_S3_KEY;
        /** @var non-empty-string $secret */
        $secret = $env::FILESYSTEM_FLYSYSTEM_S3_SECRET;
        /** @var non-empty-string $region */
        $region = $env::FILESYSTEM_FLYSYSTEM_S3_REGION;
        /** @var non-empty-string $version */
        $version = $env::FILESYSTEM_FLYSYSTEM_S3_VERSION;
        /** @var non-empty-string $bucket */
        $bucket = $env::FILESYSTEM_FLYSYSTEM_S3_BUCKET;
        /** @var string $prefix */
        $prefix = $env::FILESYSTEM_FLYSYSTEM_S3_PREFIX;
        /** @var array<array-key, mixed> $options */
        $options = $env::FILESYSTEM_FLYSYSTEM_S3_OPTIONS;

        $clientConfig = [
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
            'region'      => $region,
            'version'     => $version,
        ];

        $container->setSingleton(
            FlysystemAwsS3Adapter::class,
            new FlysystemAwsS3Adapter(
                client: new AwsS3Client($clientConfig),
                bucket: $bucket,
                prefix: $prefix,
                options: $options
            ),
        );
    }

    /**
     * Publish the in memory filesystem service.
     */
    public static function publishInMemoryFilesystem(Container $container): void
    {
        $container->setSingleton(
            InMemoryFilesystem::class,
            new InMemoryFilesystem(),
        );
    }

    /**
     * Publish the null filesystem service.
     */
    public static function publishNullFilesystem(Container $container): void
    {
        $container->setSingleton(
            NullFilesystem::class,
            new NullFilesystem(),
        );
    }
}
