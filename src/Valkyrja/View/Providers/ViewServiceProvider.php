<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\View\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service;
use Valkyrja\Support\ServiceProvider;
use Valkyrja\View\View;

/**
 * Class ViewServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::VIEW,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindView();
    }

    /**
     * Bind the view.
     *
     * @return void
     */
    protected function bindView(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::VIEW)
                ->setClass(View::class)
                ->setDependencies([CoreComponent::APP])
        );
    }
}
