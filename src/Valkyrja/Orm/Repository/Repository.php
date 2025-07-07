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

use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Repository\Contract\Repository as Contract;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 *
 * @template T of Entity
 *
 * @implements Contract<T>
 */
class Repository implements Contract
{
    /**
     * @param class-string<T> $entity
     */
    public function __construct(
        protected Manager $manager,
        protected string $entity,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @return T|null
     */
    public function find(int|string $id): Entity|null
    {
        /** @var T $entity */
        $entity = $this->entity;
        $where  = new Where(
            value: new Value(
                name: $entity::getIdField(),
                value: $id
            ),
        );

        // TODO: Implement find() method.

        return $this->findBy($where);
    }

    /**
     * @inheritDoc
     *
     * @return T|null
     */
    public function findBy(Where ...$where): Entity|null
    {
        $table  = $this->entity::getTableName();
        $select = $this->manager->createQueryBuilder()->select($table);
        $select->withWhere(...$where);
        // TODO: Implement findBy() method.

        $statement = $this->manager->prepare((string) $select);

        return $this->mapResultsToEntity($statement->fetchAll())[0] ?? null;
    }

    /**
     * @inheritDoc
     *
     * @return T[]
     */
    public function all(): array
    {
        return $this->allBy();
    }

    /**
     * @inheritDoc
     *
     * @return T[]
     */
    public function allBy(Where ...$where): array
    {
        $table  = $this->entity::getTableName();
        $select = $this->manager->createQueryBuilder()->select($table);
        $select->withWhere(...$where);
        // TODO: Implement allBy() method.

        $statement = $this->manager->prepare((string) $select);

        return $this->mapResultsToEntity($statement->fetchAll());
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    public function create(Entity $entity): void
    {
        $table  = $entity::getTableName();
        $create = $this->manager->createQueryBuilder()->insert($table);
        // TODO: Implement create() method.

        $statement = $this->manager->prepare((string) $create);

        $this->manager->lastInsertId($table, $entity::getIdField());
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    public function update(Entity $entity): void
    {
        $table  = $entity::getTableName();
        $update = $this->manager->createQueryBuilder()->update($table);
        // TODO: Implement update() method.

        $this->manager->prepare((string) $update);
    }

    /**
     * @inheritDoc
     *
     * @param T $entity The entity
     */
    public function delete(Entity $entity): void
    {
        $table  = $entity::getTableName();
        $delete = $this->manager->createQueryBuilder()->delete($table);
        // TODO: Implement delete() method.

        $this->manager->prepare((string) $delete);
    }

    /**
     * @param array<string, mixed>[] $results The results
     *
     * @return T[]
     */
    protected function mapResultsToEntity(array $results): array
    {
        /** @var T $entity */
        $entity = $this->entity;

        return array_map(
            static fn (array $data): Entity => $entity::fromArray($data),
            $results
        );
    }
}
