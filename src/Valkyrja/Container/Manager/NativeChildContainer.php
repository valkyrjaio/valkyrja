<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
        return $this->getAliasedId($id) !== null;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $alias The alias
     *
     * @return class-string|null
     */
    #[Override]
    public function getAliasedId(string $alias): string|null
    {
        return $this->aliases[$alias]
            ?? $this->parent->aliases[$alias]
            ?? null;
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
        return isset($this->callbacks[$id])
            || isset($this->parent->callbacks[$id]);
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
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    #[Override]
    protected function getAliasedWithoutChecks(string $id, array $arguments = []): object|null
    {
        if (isset($this->aliases[$id])) {
            return parent::getAliasedWithoutChecks($id, $arguments);
        }

        $aliasedId = $this->parent->aliases[$id] ?? null;

        if ($aliasedId === null) {
            return null;
        }

        // The parent holds the target as a singleton it has not built. Resolving it
        // there would build a second copy for a request that already holds the
        // binding, so the child builds its own.
        if (isset($this->parent->singletons[$aliasedId]) && ! isset($this->parent->instances[$aliasedId])) {
            return $this->get($aliasedId, $arguments);
        }

        return $this->parent->getAliased($id, $arguments);
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
