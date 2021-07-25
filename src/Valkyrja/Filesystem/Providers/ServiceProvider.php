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

namespace Valkyrja\Filesystem\Providers;

use Aws\S3\S3Client as AwsS3Client;
use League\Flysystem\Adapter\Local as FlysystemLocalAdapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter as FlysystemAwsS3Adapter;
use League\Flysystem\Filesystem as Flysystem;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Adapters\FlysystemAdapter;
use Valkyrja\Filesystem\Drivers\Driver;
use Valkyrja\Filesystem\Filesystem;

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
            Filesystem::class            => 'publishFilesystem',
            Driver::class                => 'publishDefaultDriver',
            FlysystemAdapter::class      => 'publishFlysystemAdapter',
            FlysystemLocalAdapter::class => 'publishFlysystemLocalAdapter',
            FlysystemAwsS3Adapter::class => 'publishFlysystemAwsS3Adapter',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Filesystem::class,
            Driver::class,
            FlysystemAdapter::class,
            FlysystemLocalAdapter::class,
            FlysystemAwsS3Adapter::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Filesystem::class,
            new \Valkyrja\Filesystem\Managers\Filesystem(
                $container,
                $config['filesystem']
            )
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDefaultDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (array $config, string $adapter) use ($container): Driver {
                return new Driver(
                    $container->get(
                        $adapter,
                        [
                            $config,
                        ]
                    )
                );
            }
        );
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
        $container->setClosure(
            FlysystemAdapter::class,
            static function (array $config) use ($container) {
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
        $container->setClosure(
            FlysystemLocalAdapter::class,
            static function (array $config) {
                return new FlysystemLocalAdapter(
                    $config['dir']
                );
            }
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
        $container->setClosure(
            FlysystemAwsS3Adapter::class,
            static function (array $config) {
                $clientConfig = [
                    'credentials' => [
                        'key'    => $config['key'],
                        'secret' => $config['secret'],
                    ],
                    'region'      => $config['region'],
                    'version'     => $config['version'],
                ];

                return new FlysystemAwsS3Adapter(
                    new AwsS3Client(
                        $clientConfig
                    ),
                    $config['bucket'],
                    $config['prefix'],
                    $config['options']
                );
            }
        );
    }
}
