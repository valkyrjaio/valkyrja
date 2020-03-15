<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Repositories;

use InvalidArgumentException;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository as RepositoryContract;

use Valkyrja\ORM\Retriever;
use Valkyrja\Support\ClassHelpers;

use function get_class;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements RepositoryContract
{
    /**
     * The entity manager.
     *
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * The entity to use.
     *
     * @var string|Entity
     */
    protected string $entity;

    /**
     * The table to use.
     *
     * @var string
     */
    protected string $table;

    /**
     * Repository constructor.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     *
     * @throws InvalidArgumentException
     */
    public function __construct(EntityManager $entityManager, string $entity)
    {
        ClassHelpers::validateClass($entity, Entity::class);

        $this->entityManager = $entityManager;
        $this->entity        = $entity;
        $this->table         = $this->entity::getEntityTable();
    }

    /**
     * Make a new repository.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     *
     * @return static
     */
    public static function make(EntityManager $entityManager, string $entity): self
    {
        return new static($entityManager, $entity);
    }

    /**
     * Find by given criteria.
     *
     * @param bool|null $getRelations
     *
     * @return Retriever
     */
    public function find(bool $getRelations = false): Retriever
    {
        return $this->entityManager->find($this->entity, $getRelations);
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Retriever
     */
    public function findOne($id, bool $getRelations = false): Retriever
    {
        return $this->entityManager->findOne($this->entity, $id, $getRelations);
    }

    /**
     * Count all the results of given criteria.
     *
     * @return Retriever
     */
    public function count(): Retriever
    {
        return $this->entityManager->count($this->entity);
    }

    /**
     * Create a new model.
     * <code>
     *      $repository->create(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->entityManager->create($entity, $defer);
    }

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     * <code>
     *      $repository->save(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->entityManager->save($entity, $defer);
    }

    /**
     * Delete an existing model.
     * <code>
     *      $repository->delete(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->entityManager->delete($entity, $defer);
    }

    /**
     * Clear a model previously set for creation, save, or deletion.
     * <code>
     *      $repository->clear(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity !== null) {
            $this->validateEntity($entity);
        }

        $this->entityManager->clear($entity);
    }

    /**
     * Get a new query builder instance.
     *
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder($this->entity, $alias);
    }

    /**
     * Create a new query.
     *
     * @param string $query
     *
     * @return Query
     */
    public function createQuery(string $query): Query
    {
        return $this->entityManager->createQuery($query, $this->entity);
    }

    /**
     * Validate the passed entity.
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    protected function validateEntity(Entity $entity): void
    {
        if (! ($entity instanceof $this->entity)) {
            throw new InvalidEntityException(
                'This repository expects entities to be instances of '
                . $this->entity
                . '. Entity instanced from '
                . get_class($entity)
                . ' provided instead.'
            );
        }
    }
}
