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
use Valkyrja\Container\Service;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Support\ServiceProvider;

/**
 * Class FilesystemServiceProvider.
 *
 * @author Melech Mizrachi
 */
class FilesystemServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindFilesystem();
    }

    /**
     * Bind the filesystem.
     *
     * @return void
     */
    protected function bindFilesystem(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::FILESYSTEM)
                ->setClass(Filesystem::class)
        );
    }
}
