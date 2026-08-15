<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Manager\Contract;

use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

interface ProvidersAwareContract
{
    /**
     * Register a provider.
     *
     * @param ServiceProviderContract $provider The provider
     */
    public function register(ServiceProviderContract $provider): void;

    /**
     * Determine whether a publish callback is registered for a given service.
     *
     * @param class-string $id The provided service id
     */
    public function isDeferred(string $id): bool;

    /**
     * Determine whether a given service is published.
     *
     * @param class-string $id The provided service id
     */
    public function isPublished(string $id): bool;

    /**
     * Publish a provided service.
     *
     * @param class-string $id The provided service id
     */
    public function publish(string $id): void;
}
