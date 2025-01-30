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
use League\Flysystem\FilesystemAdapter as FlysystemFilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter as FlysystemLocalAdapter;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Adapter\Contract\Adapter;
use Valkyrja\Filesystem\Adapter\FlysystemAdapter;
use Valkyrja\Filesystem\Adapter\InMemoryAdapter;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\Driver\Driver;
use Valkyrja\Filesystem\Factory\ContainerFactory;
use Valkyrja\Filesystem\Factory\Contract\Factory;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Filesystem::class            => [self::class, 'publishFilesystem'],
            Factory::class               => [self::class, 'publishFactory'],
            Driver::class                => [self::class, 'publishDriver'],
            FlysystemAdapter::class      => [self::class, 'publishFlysystemAdapter'],
            FlysystemLocalAdapter::class => [self::class, 'publishFlysystemLocalAdapter'],
            FlysystemAwsS3Adapter::class => [self::class, 'publishFlysystemAwsS3Adapter'],
            InMemoryAdapter::class       => [self::class, 'publishInMemoryAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Filesystem::class,
            Factory::class,
            Driver::class,
            FlysystemAdapter::class,
            FlysystemLocalAdapter::class,
            FlysystemAwsS3Adapter::class,
            InMemoryAdapter::class,
        ];
    }

    /**
     * Publish the filesystem service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFilesystem(Container $container): void
    {
        /** @var array{filesystem: \Valkyrja\Filesystem\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Filesystem::class,
            new \Valkyrja\Filesystem\Filesystem(
                $container->getSingleton(Factory::class),
                $config['filesystem']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish a driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create the driver class.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the in memory adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishInMemoryAdapter(Container $container): void
    {
        $container->setCallable(
            InMemoryAdapter::class,
            [static::class, 'createInMemoryAdapter']
        );
    }

    /**
     * Create the in memory adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return InMemoryAdapter
     */
    public static function createInMemoryAdapter(Container $container, array $config): InMemoryAdapter
    {
        return new InMemoryAdapter();
    }

    /**
     * Publish the flysystem adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFlysystemAdapter(Container $container): void
    {
        $container->setCallable(
            FlysystemAdapter::class,
            [static::class, 'createFlysystemAdapter']
        );
    }

    /**
     * Create the flysystem adapter.
     *
     * @param Container $container
     * @param array{flysystemAdapter: class-string<FlysystemFilesystemAdapter>, ...} $config
     *
     * @return FlysystemAdapter
     */
    public static function createFlysystemAdapter(Container $container, array $config): FlysystemAdapter
    {
        return new FlysystemAdapter(
            new Flysystem(
                $container->get(
                    $config['flysystemAdapter'],
                    [
                        $config,
                    ]
                )
            )
        );
    }

    /**
     * Publish the flysystem local adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFlysystemLocalAdapter(Container $container): void
    {
        $container->setCallable(
            FlysystemLocalAdapter::class,
            [static::class, 'createFlysystemLocalAdapter']
        );
    }

    /**
     * Create the flysystem local adapter.
     *
     * @param array{dir: string} $config
     *
     * @return FlysystemLocalAdapter
     */
    public static function createFlysystemLocalAdapter(array $config): FlysystemLocalAdapter
    {
        return new FlysystemLocalAdapter(
            $config['dir']
        );
    }

    /**
     * Publish the flysystem s3 adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFlysystemAwsS3Adapter(Container $container): void
    {
        $container->setCallable(
            FlysystemAwsS3Adapter::class,
            [static::class, 'createFlysystemAwsS3Adapter']
        );
    }

    /**
     * Create the flysystem s3 adapter.
     *
     * @param array{key: string, secret: string, region: string, version: string, bucket: string, prefix: string, options: array<string, mixed>} $config
     *
     * @return FlysystemAwsS3Adapter
     */
    public static function createFlysystemAwsS3Adapter(array $config): FlysystemAwsS3Adapter
    {
        $clientConfig = [
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ],
            'region'      => $config['region'],
            'version'     => $config['version'],
        ];

        return new FlysystemAwsS3Adapter(
            client: new AwsS3Client($clientConfig),
            bucket: $config['bucket'],
            prefix: $config['prefix'],
            options: $config['options']
        );
    }
}
