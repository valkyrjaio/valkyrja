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

namespace Valkyrja\Container\Manager\Trait;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidArgumentException;

use function is_callable;

trait ProvidersAware
{
    /**
     * The items provided by providers that are deferred.
     *
     * @var array<class-string, class-string>
     */
    protected array $deferred = [];

    /**
     * The custom publish handler for items provided by providers that are deferred.
     *
     * @var array<class-string, callable(ContainerContract):void>
     */
    protected array $deferredCallback = [];

    /**
     * The items provided by providers that are published.
     *
     * @var array<class-string, bool>
     */
    protected array $published = [];

    /**
     * The registered providers.
     *
     * @var array<class-string<ServiceProviderContract>, bool>
     */
    protected array $registered = [];

    /**
     * The providers.
     *
     * @var class-string<ServiceProviderContract>[]
     */
    protected array $providers = [];

    /**
     * @inheritDoc
     *
     * @param class-string<ServiceProviderContract> $provider The provider
     */
    public function register(string $provider): void
    {
        // No need to re-register providers
        if ($this->isRegistered($provider)) {
            return;
        }

        $this->providers[] = $provider;

        // If the service provider is deferred
        // and its defined what services it provides
        $this->registerDeferred($provider);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    public function isDeferred(string $id): bool
    {
        return isset($this->deferred[$id])
            || isset($this->deferredCallback[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    public function isPublished(string $id): bool
    {
        return isset($this->published[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ServiceProviderContract> $provider The provider
     */
    public function isRegistered(string $provider): bool
    {
        return isset($this->registered[$provider]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    public function publish(string $id): void
    {
        // The publish method for this provided item in the provider
        $publishCallback = $this->getDeferredCallback($id);

        // If there is no callback found then this provided item doesn't exist
        if ($publishCallback === null) {
            return;
        }

        // Publish the service provider
        $publishCallback($this);

        // Set published cache only after the success of a publish (in case of error)
        $this->published[$id] = true;
    }

    /**
     * Get the deferred callback.
     *
     * @param class-string $id The id
     *
     * @return callable(ContainerContract):void|null
     */
    protected function getDeferredCallback(string $id): callable|null
    {
        return $this->deferredCallback[$id]
            ?? null;
    }

    /**
     * Publish an unpublished provided item.
     *
     * @param class-string $id The service id
     */
    protected function publishUnpublishedProvided(string $id): void
    {
        // Check if the id is provided by a provider and isn't already published
        if ($this->isDeferred($id) && ! $this->isPublished($id)) {
            // Publish the provider
            $this->publish($id);
        }
    }

    /**
     * Register a deferred provider.
     *
     * @param class-string<ServiceProviderContract> $provider The provider
     */
    protected function registerDeferred(string $provider): void
    {
        /** @var class-string<ServiceProviderContract> $providerClass */
        $providerClass    = $provider;
        $publishCallbacks = $providerClass::publishers();

        // Add the services to the service providers list
        foreach ($publishCallbacks as $provided => $publishCallback) {
            $this->deferred[$provided] = $provider;

            if (! is_callable($publishCallback)) {
                throw new ContainerInvalidArgumentException("$provided should have a valid callable");
            }

            $this->deferredCallback[$provided] = $publishCallback;
        }

        // The provider is now registered
        $this->registered[$provider] = true;
    }
}
