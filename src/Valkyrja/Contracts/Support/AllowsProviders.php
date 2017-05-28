<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Support;

/**
 * Interface AllowsProviders.
 *
 * @author Melech Mizrachi
 */
interface AllowsProviders
{
    /**
     * Register a provider.
     *
     * @param string $provider The provider
     * @param bool   $force    [optional] Whether to force regardless of deferred status
     *
     * @return void
     */
    public function register(string $provider, bool $force = false): void;

    /**
     * Determine whether a provider has been registered.
     *
     * @param string $provider The provider
     *
     * @return bool
     */
    public function isRegistered(string $provider): bool;

    /**
     * Initialize a provided service.
     *
     * @param string $serviceId The service
     *
     * @return void
     */
    public function initializeProvided(string $serviceId): void;
}
