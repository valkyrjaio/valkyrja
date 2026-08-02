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

namespace Valkyrja\Orm\Registry\Contract;

use Valkyrja\Orm\Data\EntityMetadata;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;

/**
 * The registry holds the metadata that describes an entity type. The developer
 * registers the metadata in a service provider, and the framework reads the
 * metadata by class token.
 */
interface EntityMetadataRegistryContract
{
    /**
     * Determine whether the registry holds the metadata for an entity.
     *
     * @param class-string<EntityContract> $entity The entity
     */
    public function has(string $entity): bool;

    /**
     * Get the metadata for an entity.
     *
     * The registry does not infer the metadata. The method throws when the
     * developer did not register the entity.
     *
     * @param class-string<EntityContract> $entity The entity
     *
     * @throws OrmUnregisteredEntityException When the registry holds no metadata for the entity
     */
    public function get(string $entity): EntityMetadata;

    /**
     * Create a new registry that also holds the metadata for an entity.
     *
     * @param class-string<EntityContract> $entity   The entity
     * @param EntityMetadata               $metadata The metadata
     */
    public function withEntity(string $entity, EntityMetadata $metadata): static;
}
