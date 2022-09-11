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
 * Interface Factory.
 *
 * @author Melech Mizrachi
 */
interface Factory
{
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
     * Create a driver.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The driver class name
     * @param array           $config  The config
     *
     * @return T
     */
    public function createDriver(Adapter $adapter, string $name, array $config): Driver;

    /**
     * Create a repository.
     *
     * @template T
     * @template E
     *
     * @param Driver          $driver The driver
     * @param class-string<T> $name   The repository class name
     * @param class-string<E> $entity The entity class name
     *
     * @return T<E>
     */
    public function createRepository(Driver $driver, string $name, string $entity): Repository;

    /**
     * Create a query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The query builder class name
     *
     * @return T
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder;

    /**
     * Create a delete query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The delete query builder class name
     *
     * @return T
     */
    public function createDeleteQueryBuilder(Adapter $adapter, string $name): DeleteQueryBuilder;

    /**
     * Create a insert query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The insert query builder class name
     *
     * @return T
     */
    public function createInsertQueryBuilder(Adapter $adapter, string $name): InsertQueryBuilder;

    /**
     * Create a select query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The select query builder class name
     *
     * @return T
     */
    public function createSelectQueryBuilder(Adapter $adapter, string $name): SelectQueryBuilder;

    /**
     * Create a update query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The update query builder class name
     *
     * @return T
     */
    public function createUpdateQueryBuilder(Adapter $adapter, string $name): UpdateQueryBuilder;

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
}
