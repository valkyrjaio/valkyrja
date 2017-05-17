<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Session\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service;
use Valkyrja\Session\Session;
use Valkyrja\Support\ServiceProvider;

/**
 * Class SessionServiceProvider.
 *
 * @author Melech Mizrachi
 */
class SessionServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::SESSION,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindSession();
    }

    /**
     * Bind the session.
     *
     * @return void
     */
    public function bindSession(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::SESSION)
                ->setClass(Session::class)
                ->setDependencies([CoreComponent::APP])
        );
    }
}
