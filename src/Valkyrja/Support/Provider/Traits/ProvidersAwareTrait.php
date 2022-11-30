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

use Valkyrja\Support\Provider\Provides;

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
     * The items provided by providers that are published.
     *
     * @var string[]
     */
    protected static array $published = [];

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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function isProvided(string $itemId): bool
    {
        return isset(self::$provided[$itemId]);
    }

    /**
     * @inheritDoc
     */
    public function isPublished(string $itemId): bool
    {
        return isset(self::$published[$itemId]);
    }

    /**
     * @inheritDoc
     */
    public function isRegistered(string $provider): bool
    {
        return isset(self::$registered[$provider]);
    }

    /**
     * @inheritDoc
     */
    public function publishProvided(string $itemId): void
    {
        // The provider for this provided item
        $provider = self::$provided[$itemId];
        // The publish method for this provided item in the provider
        $publishMethod = self::$providedMethod[$itemId] ?? static::$defaultPublishMethod;

        self::$published[$itemId] = true;

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
            self::$provided[$provided]       = $provider;
            self::$providedMethod[$provided] = $publishMethods[$provided] ?? static::$defaultPublishMethod;
        }

        // The provider is now registered
        self::$registered[$provider] = true;
    }
}
