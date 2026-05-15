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
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;

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
        if (! parent::isAlias($id) && $this->parent->isAlias($id)) {
            return $this->parent->getAliased($id, $arguments);
        }

        return parent::getAliasedWithoutChecks($id, $arguments);
    }
}
