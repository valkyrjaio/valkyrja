<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use InvalidArgumentException;
use PDO;
use Valkyrja\Application;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\QueryBuilder\NativeQueryBuilder;
use Valkyrja\ORM\Repositories\PDORepository;
use Valkyrja\Support\Providers\Provides;

/**
 * Class NativeEntityManager.
 */
class NativeEntityManager implements EntityManager
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected $app;

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected $repositories = [];

    /**
     * Stores.
     *
     * @var PDO[]
     */
    protected $stores = [];

    /**
     * The models awaiting to be committed for creation.
     *
     * <code>
     *      [
     *          Model::class
     *      ]
     * </code>
     *
     * @var Entity[]
     */
    protected $createModels = [];

    /**
     * The models awaiting to be committed for saving.
     *
     * <code>
     *      [
     *          Model::class
     *      ]
     * </code>
     *
     * @var \Valkyrja\ORM\Entity[]
     */
    protected $saveModels = [];

    /**
     * NativeEntityManager constructor.
     *
     * @param Application $app
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->store()->beginTransaction();
    }

    /**
     * Get a store by the connection name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return PDO
     */
    public function store(string $name = null): PDO
    {
        return $this->getStore($name);
    }

    /**
     * Get a new query builder instance.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return new NativeQueryBuilder();
    }

    /**
     * Get a repository instance.
     *
     * @param string $entity
     *
     * @return Repository
     */
    public function getRepository(string $entity): Repository
    {
        if (isset($this->repositories[$entity])) {
            return $this->repositories[$entity];
        }

        /** @var Entity|string $entity */
        $repository = $entity::getRepository() ?? PDORepository::class;

        return $this->repositories[$entity] = new $repository($this, $entity, $entity::getTable());
    }

    /**
     * Initiate a transaction.
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->store()->beginTransaction();
    }

    /**
     * Set a model for creation on transaction commit.
     *
     * @param \Valkyrja\ORM\Entity $entity
     *
     * @return void
     */
    public function create(Entity $entity): void
    {
        $id = spl_object_id($entity);

        $this->createModels[$id] = $entity;
    }

    /**
     * Set a model for saving on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function save(Entity $entity): void
    {
        $id = spl_object_id($entity);

        $this->saveModels[$id] = $entity;
    }

    /**
     * Remove a model previously set for creation or save.
     *
     * @param \Valkyrja\ORM\Entity $entity The entity instance to remove.
     *
     * @return bool
     */
    public function remove(Entity $entity): bool
    {
        // Get the id of the object
        $id = spl_object_id($entity);

        // If the model is set to be created
        if (isset($this->createModels[$id])) {
            // Unset it
            unset($this->createModels[$id]);

            return true;
        }

        // If the model is set to be saved
        if (isset($this->saveModels[$id])) {
            // Unset it
            unset($this->saveModels[$id]);

            return true;
        }

        // The model wasn't set for creation or saving
        return false;
    }

    /**
     * Commit all items in the transaction.
     *
     * @throws InvalidArgumentException
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function commit(): bool
    {
        // Iterate through the models awaiting creation
        foreach ($this->createModels as $cid => $createModel) {
            // Create the model
            $this->getRepository(\get_class($createModel))->create($createModel);
            // Unset the model
            unset($this->createModels[$cid]);
        }

        // Iterate through the models awaiting save
        foreach ($this->saveModels as $sid => $saveModel) {
            // Save the model
            $this->getRepository(\get_class($saveModel))->save($saveModel);
            // Unset the model
            unset($this->saveModels[$sid]);
        }

        return $this->store()->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->store()->rollBack();
    }

    /**
     * Get a pdo store by name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return PDO
     */
    protected function getStore(string $name = null): PDO
    {
        $name = $name ?? $this->app->config()['database']['default'];

        if (isset($this->stores[$name])) {
            return $this->stores[$name];
        }

        $config = $this->getStoreConfig($name);

        return $this->stores[$name] = $this->getStoreFromConfig($config);
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
    protected function getStoreConfig(string $name): array
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
    protected function getStoreFromConfig(array $config): PDO
    {
        $dsn = $config['driver']
            . ':host=' . $config['host']
            . ';port=' . $config['port']
            . ';dbname=' . $config['database']
            . ';charset=' . $config['charset'];

        return new PDO(
            $dsn,
            $config['username'],
            $config['password'],
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
        $app->container()->singleton(
            EntityManager::class,
            new static($app)
        );
    }
}
