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
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Support\ServiceProvider;

/**
 * Class ResponseBuilderServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ResponseBuilderServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::RESPONSE_BUILDER,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindResponseBuilder();
    }

    /**
     * Bind the response builder.
     *
     * @return void
     */
    protected function bindResponseBuilder(): void
    {
        $this->app->container()->singleton(
            CoreComponent::RESPONSE_BUILDER,
            new ResponseBuilder($this->app)
        );
    }
}
