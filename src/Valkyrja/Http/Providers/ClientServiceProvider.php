<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service;
use Valkyrja\Http\Client;
use Valkyrja\Support\ServiceProvider;

/**
 * Class ClientServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ClientServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::CLIENT,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindClient();
    }

    /**
     * Bind the client.
     *
     * @return void
     */
    protected function bindClient(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::CLIENT)
                ->setClass(Client::class)
        );
    }
}
