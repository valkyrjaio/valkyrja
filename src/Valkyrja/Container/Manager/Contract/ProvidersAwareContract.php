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

namespace Valkyrja\Container\Manager\Contract;

use Valkyrja\Container\Provider\Provider;

interface ProvidersAwareContract
{
    /**
     * Register a provider.
     *
     * @param class-string<Provider> $provider The provider
     * @param bool                   $force    [optional] Whether to force regardless of deferred status
     */
    public function register(string $provider, bool $force = false): void;

    /**
     * Check whether a given service is provided by a deferred provider.
     *
     * @param class-string $id The provided service id
     */
    public function isDeferred(string $id): bool;

    /**
     * Check whether a given service is published.
     *
     * @param class-string $id The provided service id
     */
    public function isPublished(string $id): bool;

    /**
     * Determine whether a provider has been registered.
     *
     * @param class-string<Provider> $provider The provider
     */
    public function isRegistered(string $provider): bool;

    /**
     * Initialize a provided service.
     *
     * @param class-string $id The provided service id
     */
    public function publishProvided(string $id): void;
}
