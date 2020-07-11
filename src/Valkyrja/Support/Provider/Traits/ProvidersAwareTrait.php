<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support\Provider\Traits;

/**
 * Trait AllowsProviders.
 *
 * @author Melech Mizrachi
 */
trait ProvidersAwareTrait
{
    /**
     * The items provided by providers that are deferred.
     *
     * @var string[]
     */
    protected static array $provided = [];

    /**
     * The custom publish handler for items provided by providers that are deferred.
     *
     * @var string[]
     */
    protected static array $providedMethod = [];

    /**
     * The registered providers.
     *
     * @var array
     */
    protected static array $registered = [];

    /**
     * The default publish method.
     *
     * @var string
     */
    protected static string $defaultPublishMethod = 'publish';

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

        // Helpers::validateClass($provider, Provides::class);

        /** @var Provides $provider */

        // If the service provider is deferred
        // and its defined what services it provides
        if (! $force && $provider::deferred() && $provides = $provider::provides()) {
            $this->registerDeferred($provider, ...$provides);

            return;
        }

        // Publish the service provider
        $provider::{static::$defaultPublishMethod}($this);

        // The provider is now registered
        self::$registered[$provider] = true;
    }

    /**
     * Check whether a given item is provided by a deferred provider.
     *
     * @param string $itemId The provided item id
     *
     * @return bool
     */
    public function isProvided(string $itemId): bool
    {
        return isset(self::$provided[$itemId]);
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
     * Initialize a provided item.
     *
     * @param string $itemId The provided item id
     *
     * @return void
     */
    public function initializeProvided(string $itemId): void
    {
        // The provider for this provided item
        $provider = self::$provided[$itemId];
        // The publish method for this provided item in the provider
        $publishMethod = self::$providedMethod[$itemId] ?? static::$defaultPublishMethod;

        // Publish the service provider
        $provider::$publishMethod($this);
    }

    /**
     * Register a deferred provider.
     *
     * @param string   $provider The provider
     * @param string[] $provides The provided items
     *
     * @return void
     */
    protected function registerDeferred(string $provider, string ...$provides): void
    {
        /** @var Provides $provider */
        $publishMethods = $provider::publishers();

        // Add the services to the service providers list
        foreach ($provides as $provided) {
            self::$provided[$provided] = $provider;

            if (isset($publishMethods[$provided])) {
                self::$providedMethod[$provided] = $publishMethods[$provided];
            }
        }

        // The provider is now registered
        self::$registered[$provider] = true;
    }
}
