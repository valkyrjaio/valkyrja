<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Filesystem\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Support\Provider;

/**
 * Class FilesystemServiceProvider.
 *
 * @author Melech Mizrachi
 */
class FilesystemServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::FILESYSTEM,
    ];

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindFilesystem($app);
    }

    /**
     * Bind the filesystem.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindFilesystem(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::FILESYSTEM,
            new Filesystem()
        );
    }
}
