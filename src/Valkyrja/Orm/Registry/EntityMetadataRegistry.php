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
