<?php

declare(strict_types = 1);

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
use PDO;
use Valkyrja\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\EntityPersister;
use Valkyrja\ORM\EntityPersisters\PDOEntityPersister;
use Valkyrja\ORM\EntityRetriever;
use Valkyrja\ORM\EntityRetrievers\PDOEntityRetriever;
use Valkyrja\ORM\Exceptions;
use Valkyrja\ORM\Queries\PDOQuery;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilder\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\NativeRepository;
use Valkyrja\ORM\Repository;
use Valkyrja\Support\Providers\Provides;

/**
 * Class PDOEntityManager.
 *
 * @author Melech Mizrachi
 */
class PDOEntityManager implements EntityManager
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The entity retriever.
     *
     * @var EntityRetriever
     */
    protected EntityRetriever $entityRetriever;

    /**
     * The entity persister.
     *
     * @var EntityPersister
     */
    protected EntityPersister $entityPersister;

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
     * Connections.
     *
     * @var PDO[]
     */
    protected static array $connections = [];

    /**
     * PDOEntityManager constructor.
     *
     * @param Application $app
     * @param string|null $connection
     */
    public function __construct(Application $app, string $connection = null)
    {
        $this->app             = $app;
        $this->connection      = $connection ?? $app->config()[ConfigKeyPart::DB][ConfigKeyPart::DEFAULT];
        $this->entityRetriever = new PDOEntityRetriever($this, $this->connection());
        $this->entityPersister = new PDOEntityPersister($this, $this->connection());

        $this->connection()->beginTransaction();
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
        $queryBuilder = new SqlQueryBuilder($this);

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
        $pdoQuery = new PDOQuery($this->connection());

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
     * @return Repository
     */
    public function repository(string $entity): Repository
    {
        if (isset($this->repositories[$entity])) {
            return $this->repositories[$entity];
        }

        /** @var Entity|string $entity */
        $repository = $entity::getRepository() ?? NativeRepository::class;

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
        return $this->connection()->rollBack();
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

        return $this->entityRetriever->find($entity, $id, $getRelations);
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
    public function findBy(
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
            return $this->repository($entity)->findBy($criteria, $orderBy, $limit, $offset, $columns, $getRelations);
        }

        return $this->entityRetriever->findBy($entity, $criteria, $orderBy, $limit, $offset, $columns, $getRelations);
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
     * @return Entity
     */
    public function findOneBy(
        string $entity,
        bool $useRepository,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): Entity {
        if ($useRepository) {
            return $this->repository($entity)->findOneBy($criteria, $orderBy, $offset, $columns, $getRelations);
        }

        return $this->entityRetriever->findOneBy($entity, $criteria, $orderBy, $offset, $columns, $getRelations);
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

        return $this->entityRetriever->findAll($entity, $orderBy, $columns, $getRelations);
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

    /**
     * Get a pdo store by name.
     *
     * @return PDO
     */
    protected function connection(): PDO
    {
        if (isset(self::$connections[$this->connection])) {
            return self::$connections[$this->connection];
        }

        $config = $this->getConnectionConfig($this->connection);

        return self::$connections[$this->connection] = $this->getConnectionFromConfig($config);
    }

    /**
     * Get the store config.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getConnectionConfig(string $name): array
    {
        $config = $this->app->config('database.connections.' . $name);

        if (null === $config) {
            throw new InvalidArgumentException('Invalid connection name specified: ' . $name);
        }

        return $config;
    }

    /**
     * Get the store from the config.
     *
     * @param array $config
     *
     * @return PDO
     */
    protected function getConnectionFromConfig(array $config): PDO
    {
        $dsn = $config[ConfigKeyPart::DRIVER]
            . ':host=' . $config[ConfigKeyPart::HOST]
            . ';port=' . $config[ConfigKeyPart::PORT]
            . ';dbname=' . $config[ConfigKeyPart::DB]
            . ';charset=' . $config[ConfigKeyPart::CHARSET];

        return new PDO(
            $dsn,
            $config[ConfigKeyPart::USERNAME],
            $config[ConfigKeyPart::PASSWORD],
            []
        );
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            EntityManager::class,
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
        $app->container()->singleton(EntityManager::class, new static($app));
    }
}
