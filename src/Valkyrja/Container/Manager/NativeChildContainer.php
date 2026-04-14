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

namespace Valkyrja\Container\Manager;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

class NativeChildContainer extends Container
{
    public function __construct(
        protected Container $parent
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isAlias(string $id): bool
    {
        return $this->getAlias($id) !== null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isService(string $id): bool
    {
        return $this->getServiceCallable($id) !== null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingletonBinding(string $id): bool
    {
        return isset($this->singletons[$id])
            || isset($this->parent->singletons[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingletonInstance(string $id): bool
    {
        return isset($this->instances[$id])
            || isset($this->parent->instances[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The provided service id
     */
    #[Override]
    public function isDeferred(string $id): bool
    {
        return isset($this->deferred[$id])
            || isset($this->deferredCallback[$id])
            || isset($this->parent->deferred[$id])
            || isset($this->parent->deferredCallback[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The provided service id
     */
    #[Override]
    public function isPublished(string $id): bool
    {
        return isset($this->published[$id])
            || isset($this->parent->published[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ServiceProviderContract> $provider The provider
     */
    #[Override]
    public function isRegistered(string $provider): bool
    {
        return isset($this->registered[$provider])
            || isset($this->parent->registered[$provider]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @return callable(ContainerContract):void|null
     */
    #[Override]
    protected function getDeferredCallback(string $id): callable|null
    {
        return $this->deferredCallback[$id]
            ?? $this->parent->deferredCallback[$id]
            ?? null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @return class-string|null
     */
    #[Override]
    protected function getAlias(string $id): string|null
    {
        return $this->aliases[$id]
            ?? $this->parent->aliases[$id]
            ?? null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    protected function getSingletonInstance(string $id): object|null
    {
        return $this->instances[$id]
            ?? $this->parent->instances[$id]
            ?? null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @return callable(ContainerContract, array<array-key, mixed>):object|null
     */
    #[Override]
    protected function getServiceCallable(string $id): callable|null
    {
        return $this->services[$id]
            ?? $this->parent->services[$id]
            ?? null;
    }
}
