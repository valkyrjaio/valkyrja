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
use Valkyrja\Contracts\Application;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Support\Provider;

/**
 * Class ResponseBuilderServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ResponseBuilderServiceProvider extends Provider
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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return static::$provides;
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
        static::bindResponseBuilder($app);
    }

    /**
     * Bind the response builder.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindResponseBuilder(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::RESPONSE_BUILDER,
            new ResponseBuilder($app)
        );
    }
}
