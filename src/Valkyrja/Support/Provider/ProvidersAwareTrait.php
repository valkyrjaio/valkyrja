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

namespace Valkyrja\Support\Provider;

use;

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
     * @var array<string, string>
     */
    protected array $provided = [];

    /**
     * The custom publish handler for items provided by providers that are deferred.
     *
     * @var array<string, callable>
     */
    protected array $providedCallback = [];

    /**
     * The items provided by providers that are published.
     *
     * @var array<string, bool>
     */
    protected array $published = [];

    /**
     * The registered providers.
     *
     * @var array<class-string, bool>
     */
    protected array $registered = [];

    /**
     * The default publish method.
     *
     * @var string
     */
    protected string $defaultPublishMethod = 'publish';

    /**
     * @inheritDoc
     *
     * @param class-string $provider The provider
     */
    public function register(string $provider, bool $force = false): void
    {
        // No need to re-register providers
        if ($this->isRegistered($provider)) {
            return;
        }

        // Helpers::validateClass($provider, Provides::class);

        /** @var \Valkyrja\Support\Provider\Provides $providerClass */
        $providerClass = $provider;

        // If the service provider is deferred
        // and its defined what services it provides
        if (! $force && $providerClass::deferred() && $provides = $providerClass::provides()) {
            $this->registerDeferred($provider, ...$provides);

            return;
        }

        // Publish the service provider
        $providerClass::{$this->defaultPublishMethod}($this);

        // The provider is now registered
        $this->registered[$provider] = true;
    }

    /**
     * @inheritDoc
     */
    public function isProvided(string $itemId): bool
    {
        return isset($this->provided[$itemId]);
    }

    /**
     * @inheritDoc
     */
    public function isPublished(string $itemId): bool
    {
        return isset($this->published[$itemId]);
    }

    /**
     * @inheritDoc
     */
    public function isRegistered(string $provider): bool
    {
        return isset($this->registered[$provider]);
    }

    /**
     * @inheritDoc
     */
    public function publishProvided(string $itemId): void
    {
        // The provider for this provided item
        $provider = $this->provided[$itemId];
        // The publish method for this provided item in the provider
        $publishCallback = $this->providedCallback[$itemId]
            ?? [$provider, $this->defaultPublishMethod];

        // Publish the service provider
        $publishCallback($this);

        // Set published cache only after the success of a publish (in case of error)
        $this->published[$itemId] = true;
    }

    /**
     * Publish an unpublished provided item.
     *
     * @param string $itemId The item id
     *
     * @return void
     */
    protected function publishUnpublishedProvided(string $itemId): void
    {
        // Check if the id is provided by a provider and isn't already published
        if ($this->isProvided($itemId) && ! $this->isPublished($itemId)) {
            // Publish the provider
            $this->publishProvided($itemId);
        }
    }

    /**
     * Register a deferred provider.
     *
     * @param class-string $provider The provider
     * @param string[]     $provides The provided items
     *
     * @return void
     */
    protected function registerDeferred(string $provider, string ...$provides): void
    {
        /** @var \Valkyrja\Support\Provider\Provides $providerClass */
        $providerClass   = $provider;
        $publishCallback = $providerClass::publishers();

        // Add the services to the service providers list
        foreach ($provides as $provided) {
            $this->provided[$provided]         = $provider;
            $this->providedCallback[$provided] = $publishCallback[$provided]
                ?? [$provider, $this->defaultPublishMethod];
        }

        // The provider is now registered
        $this->registered[$provider] = true;
    }
}
