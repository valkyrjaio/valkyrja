<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Registry;

use Override;
use Valkyrja\Orm\Data\EntityMetadata;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;

class EntityMetadataRegistry implements EntityMetadataRegistryContract
{
    /**
     * @param array<class-string<EntityContract>, EntityMetadata> $metadata [optional] The metadata by entity
     */
    public function __construct(
        protected array $metadata = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $entity): bool
    {
        return isset($this->metadata[$entity]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $entity): EntityMetadata
    {
        return $this->metadata[$entity]
            ?? throw new OrmUnregisteredEntityException(
                "Entity $entity has no registered metadata. Register the entity in a service provider."
            );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withEntity(string $entity, EntityMetadata $metadata): static
    {
        $new = clone $this;

        $new->metadata[$entity] = $metadata;

        return $new;
    }
}
