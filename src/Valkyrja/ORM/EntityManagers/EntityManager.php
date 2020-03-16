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
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\Support\ClassHelpers;
use Valkyrja\Support\Exceptions\InvalidClassProvidedException;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\Config\Configs\ORMConfig;

/**
 * Class EntityManager.
 *
 * @author Melech Mizrachi
 */
class EntityManager implements EntityManagerContract
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The config.
     *
     * @var ORMConfig|array
     */
    protected $config;

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
     */
    public function __construct(Application $app)
    {
        $this->config         = $app->config()['orm'];
        $this->app            = $app;
        $this->defaultAdapter = $this->config['connections'][$this->config['default']]['adapter'] ?? CKP::PDO;
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
     * Get an adapter.
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

        /** @var Adapter $adapter */
        $adapter = $this->config['adapters'][$name];

        return $adapter::make();
    }

    /**
     * Get a connection.
     *
     * @param string|null $connection
     *
     * @return Connection
     */
    public function getConnection(string $connection = null): Connection
    {
        return $this->getAdapter()->getConnection($connection);
    }

    /**
     * Create a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        return $this->getConnection()->createQueryBuilder($entity, $alias);
    }

    /**
     * Create a new query instance.
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
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return $this->getConnection()->createRetriever();
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->getConnection()->getPersister();
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
        /** @var Repository $repository */
        $repository = $entity::getEntityRepository();

        return $this->repositories[$entity] = $repository::make($this, $entity);
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
     * @return bool
     */
    public function commit(): bool
    {
        $this->getConnection()->getPersister()->persist();

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
     * Find by given criteria.
     * <code>
     *      $entityManager
     *          ->findBy(
     *              Entity::class,
     *              1
     *          )
     * </code>.
     *
     * @param string    $entity
     * @param bool|null $getRelations
     *
     * @return Retriever
     */
    public function find(string $entity, bool $getRelations = false): Retriever
    {
        return $this->getConnection()->createRetriever()->find($entity, $getRelations);
    }

    /**
     * Find a single entity given its id.
     * <code>
     *      $entityManager
     *          ->find(
     *              Entity::class,
     *              1,
     *              true | false | null
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Retriever
     */
    public function findOne(string $entity, $id, bool $getRelations = false): Retriever
    {
        return $this->getConnection()->createRetriever()->findOne($entity, $id, $getRelations);
    }

    /**
     * Count all the results of given criteria.
     * <code>
     *      $entityManager
     *          ->count(
     *              Entity::class
     *          )
     * </code>.
     *
     * @param string $entity
     *
     * @return Retriever
     */
    public function count(string $entity): Retriever
    {
        return $this->getConnection()->createRetriever()->count($entity);
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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->create($entity, $defer);
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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->save($entity, $defer);
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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->delete($entity, $defer);
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
        $this->getConnection()->getPersister()->clear($entity);
    }
}
