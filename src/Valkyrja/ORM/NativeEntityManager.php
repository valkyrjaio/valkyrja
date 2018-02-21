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

use PDO;
use Valkyrja\Application;
use Valkyrja\Model\Model;
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
     * The PDO.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * The query builder.
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected $repositories = [];

    /**
     * The models awaiting to be committed for creation.
     *
     * <code>
     *      [
     *          Model::class
     *      ]
     * </code>
     *
     * @var Model[]
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
     * @var Model[]
     */
    protected $saveModels = [];

    /**
     * NativeEntityManager constructor.
     *
     * @param PDO          $pdo
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(PDO $pdo, QueryBuilder $queryBuilder)
    {
        $this->pdo          = $pdo;
        $this->queryBuilder = $queryBuilder;

        $this->beginTransaction();
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * Get a new query builder instance.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return new $this->queryBuilder();
    }

    /**
     * Get a repository instance.
     *
     * @param string $model
     *
     * @return Repository
     */
    public function getRepository(string $model): Repository
    {
        if (isset($this->repositories[$model])) {
            return $this->repositories[$model];
        }

        /** @var Model|string $model */
        $repository = $model::getRepository() ?? PDORepository::class;

        return $this->repositories[$model] = new $repository($this, $model, $model::getTable());
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Set a model for creation on transaction commit.
     *
     * @param Model $model
     *
     * @return void
     */
    public function create(Model $model): void
    {
        $id = spl_object_id($model);

        $this->createModels[$id] = $model;
    }

    /**
     * Set a model for saving on transaction commit.
     *
     * @param Model $model
     *
     * @return void
     */
    public function save(Model $model): void
    {
        $id = spl_object_id($model);

        $this->saveModels[$id] = $model;
    }

    /**
     * Remove a model previously set for creation or save.
     *
     * @param Model $model The entity instance to remove.
     *
     * @return bool
     */
    public function remove(Model $model): bool
    {
        // Get the id of the object
        $id = spl_object_id($model);

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

        return $this->pdo->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
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
     * @return void
     */
    public static function publish(Application $app): void
    {
        $default  = config()['database']['default'];
        $database = config()['database']['connections'][$default];

        $dsn = $database['driver']
            . ':host=' . $database['host']
            . ';port=' . $database['port']
            . ';dbname=' . $database['database']
            . ';charset=' . $database['charset'];

        $pdo = new PDO(
            $dsn,
            $database['username'],
            $database['password'],
            []
        );

        $app->container()->singleton(
            EntityManager::class,
            new static($pdo, new NativeQueryBuilder())
        );
    }
}
