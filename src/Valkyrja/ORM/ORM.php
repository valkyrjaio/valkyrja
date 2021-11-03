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

namespace Valkyrja\ORM;

/**
 * Interface ORM.
 *
 * @author Melech Mizrachi
 */
interface ORM
{
    /**
     * Use a connection by name.
     *
     * @param string|null $name    The connection name
     * @param string|null $adapter The adapter
     *
     * @return Driver
     */
    public function useConnection(string $name = null, string $adapter = null): Driver;

    /**
     * Create an adapter.
     *
     * @template T
     *
     * @param class-string<T> $name   The adapter class name
     * @param array           $config The config
     *
     * @return T
     */
    public function createAdapter(string $name, array $config): Adapter;

    /**
     * Create a query builder.
     *
     * @template QueryBuilder
     *
     * @param Adapter                    $adapter The adapter
     * @param class-string<QueryBuilder> $name    The query builder class name
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder;

    /**
     * Create a query.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The query class name
     *
     * @return T
     */
    public function createQuery(Adapter $adapter, string $name): Query;

    /**
     * Create a retriever.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The retriever class name
     *
     * @return T
     */
    public function createRetriever(Adapter $adapter, string $name): Retriever;

    /**
     * Create a persister.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The persister class name
     *
     * @return T
     */
    public function createPersister(Adapter $adapter, string $name): Persister;

    /**
     * Get a repository by entity name.
     *
     * @template T
     *
     * @param class-string<T> $entity
     *
     * @return Repository<T>
     */
    public function getRepository(string $entity): Repository;

    /**
     * Get a repository from an entity class.
     *
     * @template T
     *
     * @param Entity|T $entity
     *
     * @return Repository<T>
     */
    public function getRepositoryFromClass(Entity $entity): Repository;

    /**
     * Create a statement.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The statement class name
     * @param array           $data    [optional] Additional data required for the statement
     *
     * @return T
     */
    public function createStatement(Adapter $adapter, string $name, array $data = []): Statement;

    /**
     * Create a migration.
     *
     * @template T
     *
     * @param class-string<T> $name The migration class name
     * @param array           $data [optional] Additional data required for the migration
     *
     * @return T
     */
    public function createMigration(string $name, array $data = []): Migration;

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool;

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void;

    /**
     * Persist all entities.
     *
     * @return bool
     */
    public function persist(): bool;

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool;

    /**
     * Get the last inserted id.
     *
     * @param string|null $table   [optional] The table last inserted into
     * @param string|null $idField [optional] The id field of the table last inserted into
     *
     * @return string
     */
    public function lastInsertId(string $table = null, string $idField = null): string;

    /**
     * Find by given criteria.
     *
     * <code>
     *      $entityManager->find(Entity::class, true | false)
     * </code>
     *
     * @param string $entity
     *
     * @return Retriever
     */
    public function find(string $entity): Retriever;

    /**
     * Find a single entity given its id.
     *
     * <code>
     *      $entityManager->findOne(Entity::class, 1, true | false)
     * </code>
     *
     * @param string     $entity
     * @param string|int $id
     *
     * @return Retriever
     */
    public function findOne(string $entity, $id): Retriever;

    /**
     * Count all the results of given criteria.
     *
     * <code>
     *      $entityManager
     *          ->count(
     *              Entity::class
     *          )
     * </code>
     *
     * @param string $entity
     *
     * @return Retriever
     */
    public function count(string $entity): Retriever;

    /**
     * Create a new entity.
     *
     * <code>
     *      $entityManager->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void;

    /**
     * Update an existing entity.
     *
     * <code>
     *      $entityManager->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void;

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $entityManager->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void;

    /**
     * Soft delete an existing entity.
     *
     * <code>
     *      $entityManager->softDelete(new SoftDeleteEntity(), true | false)
     * </code>
     *
     * @param SoftDeleteEntity $entity
     * @param bool             $defer [optional]
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void;

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $entityManager->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity [optional] The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void;
}
