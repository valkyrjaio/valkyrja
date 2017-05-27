<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

/**
 * Trait AllowsProviders.
 *
 * @author Melech Mizrachi
 */
trait AllowsProviders
{
    /**
     * The items provided by providers that are deferred.
     *
     * @var string[]
     */
    protected static $provided = [];

    /**
     * The registered providers.
     *
     * @var array
     */
    protected static $registered = [];

    /**
     * Register a provider.
     *
     * @param string $provider The provider
     *
     * @return void
     */
    public function register(string $provider): void
    {
        // No need to re-register providers
        if ($this->isRegistered($provider)) {
            return;
        }

        /** @var \Valkyrja\Support\Provider $provider */
        $deferred = $provider::$deferred;
        $provides = $provider::$provides;

        // If the service provider is deferred
        // and its defined what services it provides
        if ($deferred && $provides) {
            // Add the services to the service providers list
            foreach ($provides as $provided) {
                self::$provided[$provided] = $provider;
            }

            return;
        }

        // Publish the service provider
        $provider::publish(app());

        self::$registered[$provider] = true;
    }

    /**
     * Determine whether a provider has been registered.
     *
     * @param string $provider The provider
     *
     * @return bool
     */
    public function isRegistered(string $provider): bool
    {
        return isset(self::$registered[$provider]);
    }

    /**
     * Initialize a provided service.
     *
     * @param string $serviceId The service
     *
     * @return void
     */
    public function initializeProvided(string $serviceId): void
    {
        /** @var \Valkyrja\Support\Provider $serviceProvider */
        $serviceProvider = self::$provided[$serviceId];
        // The original value for the service provider's deferred status
        $originalDeferred = $serviceProvider::$deferred;
        // Do not defer the service provider
        $serviceProvider::$deferred = false;

        // Register the service provider
        $this->register($serviceProvider);

        // Reset back to the original value
        $serviceProvider::$deferred = $originalDeferred;
    }
}
