<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Filesystem;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Filesystem\Filesystem as FilesystemContract;
use Valkyrja\Support\Provides;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 */
class Filesystem implements FilesystemContract
{
    use Provides;

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::FILESYSTEM,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::FILESYSTEM,
            new Filesystem()
        );
    }
}
