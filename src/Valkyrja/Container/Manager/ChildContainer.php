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
use Valkyrja\Container\Throwable\Exception\ContainerCyclicAliasException;

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
    public function isPublished(string $id): bool
    {
        return parent::isPublished($id)
            || $this->parent->isPublished($id);
    }

    /**
     * @inheritDoc
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

        $target = $this->getParentAliasTarget($id);

        if ($target === null) {
            return null;
        }

        // The parent holds the target as a singleton it has not built. Resolving it
        // there would build a second copy for a request that already holds the
        // binding, so the child builds its own.
        if ($this->parent->isSingletonBinding($target) && ! $this->parent->isSingletonInstance($target)) {
            return $this->get($target, $arguments);
        }

        return $this->parent->getAliased($id, $arguments);
    }

    /**
     * Walk the parent's chain of aliases to the id it ends at.
     *
     * @param class-string $id The alias
     *
     * @return class-string|null
     */
    private function getParentAliasTarget(string $id): string|null
    {
        $seen    = [];
        $current = $id;
        $target  = null;

        while (($aliasedId = $this->parent->getAliasedId($current)) !== null) {
            // bindAlias() rejects a cycle, so one here arrived through setFromData().
            // Delegating to the parent would follow it until the stack ends.
            if (isset($seen[$aliasedId])) {
                throw new ContainerCyclicAliasException($current, $aliasedId);
            }

            $seen[$aliasedId] = true;
            $target           = $aliasedId;
            $current          = $aliasedId;
        }

        return $target;
    }
}
