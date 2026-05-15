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
     * @param class-string $id The service id
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function has(string $id): bool
    {
        return isset($this->callbacks[$id])
            || isset($this->parent->callbacks[$id])
            || $this->isSingleton($id)
            || $this->isService($id)
            || $this->isAlias($id);
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
     * @param class-string $id The service id
     */
    #[Override]
    protected function publishUnpublishedProvided(string $id): void
    {
        if ((isset($this->callbacks[$id]) || isset($this->parent->callbacks[$id])) && ! $this->isPublished($id)) {
            $this->publish($id);
        }
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @return callable(ContainerContract):void|null
     */
    #[Override]
    protected function getCallback(string $id): callable|null
    {
        return $this->callbacks[$id]
            ?? $this->parent->callbacks[$id]
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
