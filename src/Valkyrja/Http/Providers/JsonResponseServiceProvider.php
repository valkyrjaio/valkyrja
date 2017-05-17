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
use Valkyrja\Http\JsonResponse;
use Valkyrja\Support\ServiceProvider;

/**
 * Class JsonResponseServiceProvider.
 *
 * @author Melech Mizrachi
 */
class JsonResponseServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::JSON_RESPONSE,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindJsonResponse();
    }

    /**
     * Bootstrap the json response.
     *
     * @return void
     */
    protected function bindJsonResponse(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::JSON_RESPONSE)
                ->setClass(JsonResponse::class)
        );
    }
}
