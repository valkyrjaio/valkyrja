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
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Support\ServiceProvider;

/**
 * Class RedirectResponseServiceProvider.
 *
 * @author Melech Mizrachi
 */
class RedirectResponseServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::REDIRECT_RESPONSE,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindRedirectResponse();
    }

    /**
     * Bootstrap the redirect response.
     *
     * @return void
     */
    protected function bindRedirectResponse(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::REDIRECT_RESPONSE)
                ->setClass(RedirectResponse::class),
            false
        );
    }
}
