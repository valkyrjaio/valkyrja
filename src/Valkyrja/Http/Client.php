<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Client as ClientContract;
use Valkyrja\Support\Provides;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 */
class Client implements ClientContract
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
            CoreComponent::CLIENT,
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
            CoreComponent::CLIENT,
            new Client()
        );
    }
}
