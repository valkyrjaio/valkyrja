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

namespace Valkyrja\ORM\Managers;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\Type\Exceptions\InvalidClassProvidedException;

use function get_class;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class ORM implements Contract
{
    use Provides;

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
    protected static array $repositories = [];

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
     * ORM constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config         = $config;
        $this->defaultAdapter = $config['connections'][$config['connection']]['adapter'] ?? CKP::PDO;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            new static(
                (array) $config['orm']
            )
        );
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

        return self::$adapters[$name] = $adapter::make($this->config);
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
        if (isset(self::$repositories[$entity])) {
            return self::$repositories[$entity];
        }

        /** @var Entity|string $entity */
        /** @var Repository $repository */
        $repository = $entity::getEntityRepository() ?? $this->config['repository'];

        return self::$repositories[$entity] = $repository::make($this, $entity);
    }

    /**
     * Get a repository from an entity class.
     *
     * @param Entity $entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    public function getRepositoryFromClass(Entity $entity): Repository
    {
        return $this->getRepository(get_class($entity));
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
     * Persist all entities.
     *
     * @return bool
     */
    public function persist(): bool
    {
        return $this->getConnection()->getPersister()->persist();
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
     *
     * <code>
     *      $entityManager->find(Entity::class, true | false)
     * </code>
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
     *
     * <code>
     *      $entityManager->findOne(Entity::class, 1, true | false)
     * </code>
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
     *
     * <code>
     *      $entityManager->count(Entity::class)
     * </code>
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
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->create($entity, $defer);
    }

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
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->save($entity, $defer);
    }

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
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->delete($entity, $defer);
    }

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
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $this->getConnection()->getPersister()->softDelete($entity, $defer);
    }

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
    public function clear(Entity $entity = null): void
    {
        $this->getConnection()->getPersister()->clear($entity);
    }
}
