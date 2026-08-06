<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Repository;

use Override;
use Valkyrja\Orm\Data\DatedMetadata;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\DatedEntityContract;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Entity\Contract\SoftDeleteEntityContract;
use Valkyrja\Orm\Factory\DateFactory;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;

/**
 * @template T of EntityContract
 *
 * @implements RepositoryContract<T>
 */
class Repository implements RepositoryContract
{
    /**
     * @param class-string<T> $entity
     */
    public function __construct(
        protected ManagerContract $manager,
        protected string $entity,
        protected EntityMetadataRegistryContract $registry,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @return T|null
     */
    #[Override]
    public function find(int|string $id): EntityContract|null
    {
        /** @var class-string<T> $entity */
        $entity = $this->entity;
        $where  = new Where(
            value: new Value(
                name: $entity::getIdField(),
                value: $id
            ),
        );

        return $this->findBy($where);
    }

    /**
     * @inheritDoc
     *
     * @return T|null
     */
    #[Override]
    public function findBy(Where ...$where): EntityContract|null
    {
        $table  = $this->entity::getTableName();
        $select = $this->manager
            ->createQueryBuilder()
            ->select($table)
            ->withWhere(...$where);

        $statement = $this->manager->prepare((string) $select);

        $fetch = $statement->fetchAllEntities($this->entity);

        return $fetch[0] ?? null;
    }

    /**
     * @inheritDoc
     *
     * @return T[]
     */
    #[Override]
    public function all(): array
    {
        return $this->allBy();
    }

    /**
     * @inheritDoc
     *
     * @return T[]
     */
    #[Override]
    public function allBy(Where ...$where): array
    {
        $table  = $this->entity::getTableName();
        $select = $this->manager
            ->createQueryBuilder()
            ->select($table)
            ->withWhere(...$where);

        $statement = $this->manager->prepare((string) $select);

        return $statement->fetchAllEntities($this->entity);
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    #[Override]
    public function create(EntityContract $entity): void
    {
        $table = $entity::getTableName();

        $this->stampDatesBeforeCreate($entity);

        $set = [];

        foreach ($entity->asStorableArray() as $key => $value) {
            $set[] = new Value(
                name: $key,
                value: $value
            );
        }

        $create = $this->manager
            ->createQueryBuilder()
            ->insert($table)
            ->withSet(...$set);

        $this->manager->prepare((string) $create);

        $id = $this->manager->lastInsertId($table, $entity::getIdField());

        $entity->__set($entity::getIdField(), $id);
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    #[Override]
    public function update(EntityContract $entity): void
    {
        $this->stampDateModified($entity);

        $this->updateChangedProperties($entity);
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    #[Override]
    public function delete(EntityContract $entity): void
    {
        if ($entity instanceof SoftDeleteEntityContract) {
            $this->stampDateDeleted($entity);

            $this->updateChangedProperties($entity);

            return;
        }

        $this->forceDelete($entity);
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    #[Override]
    public function forceDelete(EntityContract $entity): void
    {
        $table = $entity::getTableName();

        $where = new Where(
            value: new Value(
                name: $entity::getIdField(),
                value: $entity->getIdValue()
            ),
        );

        $delete = $this->manager
            ->createQueryBuilder()
            ->delete($table)
            ->withWhere($where);

        $this->manager->prepare((string) $delete);
    }

    /**
     * Stamp the created date and the modified date on a dated entity.
     *
     * @param T $entity The entity
     *
     * @throws OrmUnregisteredEntityException When the registry holds no dated metadata for the entity
     */
    protected function stampDatesBeforeCreate(EntityContract $entity): void
    {
        if (! $entity instanceof DatedEntityContract) {
            return;
        }

        $dated = $this->getDatedMetadata($entity);
        $date  = DateFactory::getFormattedDate($dated->format);

        $entity->__set($dated->dateCreatedField, $date);
        $entity->__set($dated->dateModifiedField, $date);
    }

    /**
     * Stamp the modified date on a dated entity.
     *
     * @param T $entity The entity
     *
     * @throws OrmUnregisteredEntityException When the registry holds no dated metadata for the entity
     */
    protected function stampDateModified(EntityContract $entity): void
    {
        if (! $entity instanceof DatedEntityContract) {
            return;
        }

        $dated = $this->getDatedMetadata($entity);

        $entity->__set($dated->dateModifiedField, DateFactory::getFormattedDate($dated->format));
    }

    /**
     * Stamp the deleted date on a soft delete entity.
     *
     * @param SoftDeleteEntityContract $entity The entity
     *
     * @throws OrmUnregisteredEntityException When the registry holds no soft delete metadata for the entity
     */
    protected function stampDateDeleted(SoftDeleteEntityContract $entity): void
    {
        $softDelete = $this->registry->get($entity::class)->softDelete
            ?? throw new OrmUnregisteredEntityException(
                'Entity ' . $entity::class . ' implements SoftDeleteEntityContract,'
                . ' and the registered metadata holds no soft delete metadata.'
            );

        $entity->__set($softDelete->dateDeletedField, DateFactory::getFormattedDate($softDelete->format));
    }

    /**
     * Get the dated metadata for a dated entity.
     *
     * @param DatedEntityContract $entity The entity
     *
     * @throws OrmUnregisteredEntityException When the registry holds no dated metadata for the entity
     */
    protected function getDatedMetadata(DatedEntityContract $entity): DatedMetadata
    {
        return $this->registry->get($entity::class)->dated
            ?? throw new OrmUnregisteredEntityException(
                'Entity ' . $entity::class . ' implements DatedEntityContract,'
                . ' and the registered metadata holds no dated metadata.'
            );
    }

    /**
     * Update the changed properties of an entity.
     *
     * @param T $entity The entity
     */
    protected function updateChangedProperties(EntityContract $entity): void
    {
        $table = $entity::getTableName();

        $where = new Where(
            value: new Value(
                name: $entity::getIdField(),
                value: $entity->getIdValue()
            ),
        );

        $set = [];

        foreach ($entity->asStorableChangedArray() as $key => $value) {
            $set[] = new Value(
                name: $key,
                value: $value
            );
        }

        $update = $this->manager
            ->createQueryBuilder()
            ->update($table)
            ->withWhere($where)
            ->withSet(...$set);

        $this->manager->prepare((string) $update);
    }
}
