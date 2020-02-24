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

use function get_class;

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
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

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
        $config                = $app->config();
        $this->app             = $app;
        $this->connection      = $connection ?? $config[CKP::DB][CKP::DEFAULT];
        $this->entityRetriever = new RetrieverClass($this);
        $this->entityPersister = new PersisterClass($this);
        $adapterName           = $config[CKP::DB][CKP::CONNECTIONS][$this->connection][CKP::ADAPTER] ?? CKP::PDO;
        $this->adapter         = $this->adapter($adapterName);

        $this->connection()->beginTransaction();
    }

    /**
     * The adapter.
     *
     * @param string $name
     *
     * @return Adapter
     */
    protected function adapter(string $name): Adapter
    {
        $config = $this->app->config();
        /** @var Adapter $adapter */
        $adapter = $config[CKP::DB][CKP::ADAPTERS][$name];

        return $adapter::make($this->app, $this);
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
        $app->container()->singleton(EntityManagerContract::class, new static($app));
    }

    /**
     * Get a pdo store by name.
     *
     * @param string|null $connection
     *
     * @return Connection
     */
    public function connection(string $connection = null): Connection
    {
        $connection = $connection ?? $this->connection;

        return self::$connections[$connection] ?? (self::$connections[$connection] = $this->connection($connection));
    }

    /**
     * Get a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function queryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        $queryBuilder = $this->adapter->queryBuilder();

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }

    /**
     * Start a query.
     *
     * @param string      $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function query(string $query, string $entity = null): Query
    {
        $pdoQuery = $this->connection()->query();

        if (null !== $entity) {
            $pdoQuery->entity($entity);
        }

        return $pdoQuery->prepare($query);
    }

    /**
     * Get a repository instance.
     *
     * @param string $entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    public function repository(string $entity): Repository
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
        return $this->connection()->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->connection()->inTransaction();
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

        return $this->connection()->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->connection()->rollback();
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->connection()->lastInsertId();
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
     * @param bool       $useRepository
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function find(string $entity, bool $useRepository, $id, bool $getRelations = null): ?Entity
    {
        if ($useRepository) {
            return $this->repository($entity)->find($id, $getRelations);
        }

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
     * @param bool       $useRepository
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
        bool $useRepository,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): ?Entity {
        if ($useRepository) {
            return $this->repository($entity)->findBy($criteria, $orderBy, $offset, $columns, $getRelations);
        }

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
     * @param bool       $useRepository
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        bool $useRepository,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        if ($useRepository) {
            return $this->repository($entity)->findAll($orderBy, $columns, $getRelations);
        }

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
     * @param bool       $useRepository
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
        bool $useRepository,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        if ($useRepository) {
            return $this->repository($entity)->findAllBy($criteria, $orderBy, $limit, $offset, $columns, $getRelations);
        }

        ClassHelpers::validateClass($entity, Entity::class);

        return $this->entityRetriever->findAllBy(
            $entity, $criteria, $orderBy, $limit, $offset, $columns, $getRelations
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
     * @param bool   $useRepository
     * @param array  $criteria
     *
     * @return int
     */
    public function count($entity, bool $useRepository, array $criteria): int
    {
        if ($useRepository) {
            return $this->repository($entity)->count($criteria);
        }

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
     * @param bool   $useRepository
     *
     * @return void
     */
    public function create(Entity $entity, bool $useRepository): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }

        if ($useRepository) {
            $this->repository(get_class($entity))->create($entity);

            return;
        }

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
     * @param bool   $useRepository
     *
     * @return void
     */
    public function save(Entity $entity, bool $useRepository): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }

        if ($useRepository) {
            $this->repository(get_class($entity))->save($entity);

            return;
        }

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
     * @param bool   $useRepository
     *
     * @return void
     */
    public function delete(Entity $entity, bool $useRepository): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }

        if ($useRepository) {
            $this->repository(get_class($entity))->delete($entity);

            return;
        }

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
