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

namespace Valkyrja\Support\Provider\Contract;

/**
 * Interface ProvidersAware.
 *
 * @author Melech Mizrachi
 */
interface ProvidersAware
{
    /**
     * Register a provider.
     *
     * @param class-string $provider The provider
     * @param bool         $force    [optional] Whether to force regardless of deferred status
     *
     * @return void
     */
    public function register(string $provider, bool $force = false): void;

    /**
     * Check whether a given item is provided by a deferred provider.
     *
     * @param string $itemId The provided item
     *
     * @return bool
     */
    public function isDeferred(string $itemId): bool;

    /**
     * Check whether a given item is published.
     *
     * @param string $itemId The provided item id
     *
     * @return bool
     */
    public function isPublished(string $itemId): bool;

    /**
     * Determine whether a provider has been registered.
     *
     * @param class-string $provider The provider
     *
     * @return bool
     */
    public function isRegistered(string $provider): bool;

    /**
     * Initialize a provided item.
     *
     * @param string $itemId The provided item id
     *
     * @return void
     */
    public function publishProvided(string $itemId): void;
}
