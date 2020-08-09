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
use Valkyrja\Filesystem\Filesystem;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Filesystem::class            => 'publishFilesystem',
            FlysystemAdapter::class      => 'publishFlysystemAdapter',
            FlysystemLocalAdapter::class => 'publishFlysystemLocalAdapter',
            FlysystemAwsS3Adapter::class => 'publishFlysystemAwsS3Adapter',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Filesystem::class,
            FlysystemAdapter::class,
            FlysystemLocalAdapter::class,
            FlysystemAwsS3Adapter::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
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
     * Publish the flysystem adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFlysystemAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');
        $disks  = $config['filesystem']['disks'];

        $container->setClosure(
            FlysystemAdapter::class,
            static function (string $disk) use ($container, $disks) {
                return new FlysystemAdapter(
                    new Flysystem(
                        $container->get(
                            $disks[$disk]['flysystemAdapter'],
                            [
                                $disk,
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
        $config = $container->getSingleton('config');
        $disks  = $config['filesystem']['disks'];

        $container->setClosure(
            FlysystemLocalAdapter::class,
            static function (string $disk) use ($disks) {
                return new FlysystemLocalAdapter(
                    $disks[$disk]['dir']
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
        $config = $container->getSingleton('config');
        $disks  = $config['filesystem']['disks'];

        $container->setClosure(
            FlysystemAwsS3Adapter::class,
            static function (string $disk) use ($disks) {
                $s3Config     = $disks[$disk];
                $clientConfig = [
                    'credentials' => [
                        'key'    => $s3Config['key'],
                        'secret' => $s3Config['secret'],
                    ],
                    'region'      => $s3Config['region'],
                    'version'     => $s3Config['version'],
                ];

                return new FlysystemAwsS3Adapter(
                    new AwsS3Client(
                        $clientConfig
                    ),
                    $s3Config['bucket'],
                    $s3Config['prefix'],
                    $s3Config['options']
                );
            }
        );
    }
}
