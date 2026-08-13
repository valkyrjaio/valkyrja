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
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Container\Throwable\Exception\ContainerUnresolvedParentAliasException;

class ChildContainer extends Container
{
    public function __construct(
        protected ContainerContract $parent,
        ContainerData $data,
    ) {
        parent::__construct();

        $this->singletons = $data->singletons;
        $this->callbacks  = $data->callbacks;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isAlias(string $id): bool
    {
        return parent::isAlias($id)
            || $this->parent->isAlias($id);
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
        return parent::getAliasedId($alias)
            ?? $this->parent->getAliasedId($alias);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isService(string $id): bool
    {
        return parent::isService($id)
            || $this->parent->isService($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingletonInstance(string $id): bool
    {
        return parent::isSingletonInstance($id)
            || $this->parent->isSingletonInstance($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The provided service id
     */
    #[Override]
    public function isDeferred(string $id): bool
    {
        return parent::isDeferred($id)
            || $this->parent->isDeferred($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The provided service id
     */
    #[Override]
    public function isPublished(string $id): bool
    {
        return parent::isPublished($id)
            || $this->parent->isPublished($id);
    }

    /**
     * @inheritDoc
     *
     * Singleton resolution order:
     *   1. Child's own cached instance
     *   2. Parent's cached instance (already resolved, safe to reuse)
     *   3. Create fresh in child from local binding (isolates from parent)
     *
     * @param class-string $id The service id
     */
    #[Override]
    protected function getSingletonWithoutChecks(string $id): object|null
    {
        // Parent already has a resolved instance — reuse it (frozen, safe)
        // and the child has none of its own
        if (! parent::isSingletonInstance($id) && $this->parent->isSingletonInstance($id)) {
            return $this->parent->getSingleton($id);
        }

        return parent::getSingletonWithoutChecks($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    #[Override]
    protected function getServiceWithoutChecks(string $id, array $arguments = []): object|null
    {
        if (! parent::isService($id) && $this->parent->isService($id)) {
            return $this->parent->getService($id, $arguments);
        }

        return parent::getServiceWithoutChecks($id, $arguments);
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
        if (parent::isAlias($id)) {
            return parent::getAliasedWithoutChecks($id, $arguments);
        }

        if (! $this->parent->isAlias($id)) {
            return null;
        }

        $this->validateParentAliasResolution($id);

        return $this->parent->getAliased($id, $arguments);
    }

    /**
     * Validate that the parent answers an alias without caching anything new.
     *
     * @param class-string $id The alias
     */
    protected function validateParentAliasResolution(string $id): void
    {
        $seen    = [];
        $current = $id;

        while (($aliasedId = $this->parent->getAliasedId($current)) !== null) {
            if (isset($seen[$aliasedId])) {
                throw new ContainerInvalidReferenceException($id);
            }

            $seen[$aliasedId] = true;
            $current          = $aliasedId;

            if ($this->isUnresolvedInParent($current)) {
                throw new ContainerUnresolvedParentAliasException($id, $current);
            }

            // The parent answers a singleton or a service before it follows an
            // alias, so it never reaches the rest of the chain.
            if ($this->parent->isSingletonInstance($current) || $this->parent->isService($current)) {
                return;
            }
        }
    }

    /**
     * Check whether the parent would cache a given id for the first time.
     *
     * @param class-string $id The service id
     */
    protected function isUnresolvedInParent(string $id): bool
    {
        // The parent publishes before it reads any map, so this test comes first.
        // It is the same test publishUnpublishedProvided() makes.
        if ($this->parent->isDeferred($id) && ! $this->parent->isPublished($id)) {
            return true;
        }

        if ($this->parent->isSingletonInstance($id)) {
            return false;
        }

        return $this->parent->isSingletonBinding($id);
    }
}
