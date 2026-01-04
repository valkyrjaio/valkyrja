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

namespace Valkyrja\Orm\Repository;

use Override;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;

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
        $select = $this->manager->createQueryBuilder()->select($table);
        $select->withWhere(...$where);

        $statement = $this->manager->prepare((string) $select);

        $fetch = $statement->fetchAll($this->entity);

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
        $select = $this->manager->createQueryBuilder()->select($table);
        $select->withWhere(...$where);

        $statement = $this->manager->prepare((string) $select);

        return $statement->fetchAll($this->entity);
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

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    #[Override]
    public function delete(EntityContract $entity): void
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
}
