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
