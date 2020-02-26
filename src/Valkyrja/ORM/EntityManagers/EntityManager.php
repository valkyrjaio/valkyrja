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

namespace Valkyrja\ORM\EntityManagers;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager as EntityManagerContract;
use Valkyrja\ORM\Exceptions;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Persisters\Persister as PersisterClass;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Retrievers\Retriever as RetrieverClass;
use Valkyrja\Support\ClassHelpers;
use Valkyrja\Support\Exceptions\InvalidClassProvidedException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class EntityManager.
 *
 * @author Melech Mizrachi
 */
class EntityManager implements EntityManagerContract
{
    use Provides;

    /**
     * Connections.
     *
     * @var Connection[]
     */
    protected static array $connections = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The entity retriever.
     *
     * @var Retriever
     */
    protected Retriever $entityRetriever;

    /**
     * The entity persister.
     *
     * @var Persister
     */
    protected Persister $entityPersister;

    /**
     * The connection to use.
     *
     * @var string
     */
    protected string $connection;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * Adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected array $repositories = [];

    /**
     * EntityManager constructor.
     *
     * @param Application $app
     * @param string|null $connection
     */
    public function __construct(Application $app, string $connection = null)
    {
        $this->config          = $app->config()[CKP::DB];
        $this->app             = $app;
        $this->connection      = $connection ?? $this->config[CKP::DEFAULT];
        $this->defaultAdapter  = $this->config[CKP::CONNECTIONS][$this->connection][CKP::ADAPTER] ?? CKP::PDO;
        $this->entityRetriever = new RetrieverClass($this);
        $this->entityPersister = new PersisterClass($this);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            EntityManagerContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(EntityManagerContract::class, new static($app));
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        if (isset(self::$adapters[$name])) {
            return self::$adapters[$name];
        }

        $config = $this->app->config();
        /** @var Adapter $adapter */
        $adapter = $config[CKP::DB][CKP::ADAPTERS][$name];

        return $adapter::make($this->app, $this);
    }

    /**
     * Get a connection by name.
     *
     * @param string|null $connection
     *
     * @return Connection
     */
    public function getConnection(string $connection = null): Connection
    {
        $connection ??= $this->connection;

        return self::$connections[$connection]
            ?? (self::$connections[$connection] = $this->getAdapter()->createConnection($connection));
    }

    /**
     * Create a new query builder.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        return $this->getAdapter()->createQueryBuilder($entity, $alias);
    }

    /**
     * Create a new query.
     *
     * @param string|null $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function createQuery(string $query = null, string $entity = null): Query
    {
        return $this->getConnection()->createQuery($query, $entity);
    }

    /**
     * Get a repository by entity name.
     *
     * @param string $entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    public function getRepository(string $entity): Repository
    {
        if (isset($this->repositories[$entity])) {
            return $this->repositories[$entity];
        }

        ClassHelpers::validateClass($entity, Entity::class);

        /** @var Entity|string $entity */
        $repository = $entity::getRepository();

        return $this->repositories[$entity] = new $repository($this, $entity, $entity::getTable());
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->getConnection()->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        $this->getConnection()->ensureTransaction();
    }

    /**
     * Commit all items in the transaction.
     *
     * @throws Exceptions\ExecuteException
     *
     * @return bool
     */
    public function commit(): bool
    {
        $this->entityPersister->persist();

        return $this->getConnection()->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->getConnection()->rollback();
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Find a single entity given its id.
     * <code>
     *      $repository
     *          ->find(
     *              Entity::class,
     *              true | false,
     *              1,
     *              true | false | null
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function find(string $entity, $id, bool $getRelations = false): ?Entity
    {
        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->find($entity, $id, $getRelations);
    }

    /**
     * Find one entity by given criteria.
     * <code>
     *      $repository
     *          ->findOneBy(
     *              Entity::class,
     *              true | false,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function findBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): ?Entity {
        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->findBy($entity, $criteria, $orderBy, $offset, $columns, $getRelations);
    }

    /**
     * Find entities by given criteria.
     * <code>
     *      $repository
     *          ->findBy(
     *              Entity::class,
     *              true | false,
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ]
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = false
    ): array {
        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->findAll($entity, $orderBy, $columns, $getRelations);
    }

    /**
     * Find entities by given criteria.
     * <code>
     *      $repository
     *          ->findBy(
     *              Entity::class,
     *              true | false,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAllBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): array {
        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->findAllBy(
            $entity,
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $columns,
            $getRelations
        );
    }

    /**
     * Count all the results of given criteria.
     * <code>
     *      $repository
     *          ->count(
     *              Entity::class,
     *              true | false,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ]
     *          )
     * </code>.
     *
     * @param string $entity
     * @param array  $criteria
     *
     * @return int
     */
    public function count(string $entity, array $criteria): int
    {
        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->count($entity, $criteria);
    }

    /**
     * Set a model for creation on transaction commit.
     * <code>
     *      $entityManager
     *          ->create(
     *              new Entity(),
     *              true | false
     *          )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function create(Entity $entity): void
    {
        $this->entityPersister->create($entity);
    }

    /**
     * Set a model for saving on transaction commit.
     * <code>
     *      $entityManager
     *          ->save(
     *              new Entity(),
     *              true | false
     *          )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function save(Entity $entity): void
    {
        $this->entityPersister->save($entity);
    }

    /**
     * Set a model for deletion on transaction commit.
     * <code>
     *      $entityManager
     *          ->delete(
     *              new Entity(),
     *              true | false
     *          )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function delete(Entity $entity): void
    {
        $this->entityPersister->delete($entity);
    }

    /**
     * Clear a model previously set for creation, save, or deletion.
     * <code>
     *      $entityManager
     *          ->clear(
     *              new Entity()
     *          )
     * </code>.
     *
     * @param Entity|null $entity The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        $this->entityPersister->clear($entity);
    }
}
