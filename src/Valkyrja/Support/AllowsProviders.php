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
     * @param bool   $force    [optional] Whether to force regardless of deferred status
     *
     * @return void
     */
    public function register(string $provider, bool $force = false): void
    {
        // No need to re-register providers
        if ($this->isRegistered($provider)) {
            return;
        }

        /** @var \Valkyrja\Support\Provides $provider */

        // If the service provider is deferred
        // and its defined what services it provides
        if (! $force && $provider::deferred() && $provider::provides()) {
            // Add the services to the service providers list
            foreach ($provider::provides() as $provided) {
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
        /** @var \Valkyrja\Support\Provides $provider */
        $provider = self::$provided[$serviceId];

        // Register the service provider
        $this->register($provider, true);
    }
}
