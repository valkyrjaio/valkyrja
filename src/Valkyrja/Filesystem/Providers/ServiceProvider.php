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
use Valkyrja\Filesystem\Adapters\LocalFlysystemAdapter;
use Valkyrja\Filesystem\Adapters\S3FlysystemAdapter;
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
            LocalFlysystemAdapter::class => 'publishLocalFlysystemAdapter',
            S3FlysystemAdapter::class    => 'publishS3FlysystemAdapter',
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
            LocalFlysystemAdapter::class,
            S3FlysystemAdapter::class,
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
     * Publish the filesystem service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLocalFlysystemAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            LocalFlysystemAdapter::class,
            new LocalFlysystemAdapter(
                new Flysystem(
                    new FlysystemLocalAdapter(
                        $config['filesystem']['adapters']['local']['dir']
                    )
                )
            )
        );
    }

    /**
     * Publish the filesystem service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishS3FlysystemAdapter(Container $container): void
    {
        $config       = $container->getSingleton('config');
        $s3Config     = $config['filesystem']['adapters']['s3'];
        $clientConfig = [
            'credentials' => [
                'key'    => $s3Config['key'],
                'secret' => $s3Config['secret'],
            ],
            'region'      => $s3Config['region'],
            'version'     => $s3Config['version'],
        ];

        $container->setSingleton(
            S3FlysystemAdapter::class,
            new S3FlysystemAdapter(
                new Flysystem(
                    new FlysystemAwsS3Adapter(
                        new AwsS3Client(
                            $clientConfig
                        ),
                        $s3Config['bucket'],
                        $s3Config['prefix'],
                        $s3Config['options']
                    )
                )
            )
        );
    }
}
