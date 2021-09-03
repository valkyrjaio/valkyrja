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

namespace Valkyrja\ORM\Adapters;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever;
use Valkyrja\Support\Type\Cls;

/**
 * Abstract Class Adapter.
 *
 * @author Melech Mizrachi
 */
abstract class Adapter implements Contract
{
    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The entity persister.
     *
     * @var Persister
     */
    protected Persister $persister;

    /**
     * The query service to use.
     *
     * @var string
     */
    protected string $queryClass;

    /**
     * The query builder service to use.
     *
     * @var string
     */
    protected string $queryBuilderClass;

    /**
     * The persister service to use.
     *
     * @var string
     */
    protected string $persisterClass;

    /**
     * The retriever service to use.
     *
     * @var string
     */
    protected string $retrieverClass;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Adapter constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container = $container;
        $this->config    = $config;

        $this->queryClass = $this->config['query'];
        $this->queryBuilderClass = $this->config['queryBuilder'];
        $this->persisterClass = $this->config['persister'];
        $this->retrieverClass = $this->config['retriever'];
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
        /** @var Query $queryInstance */
        $queryInstance = Cls::getDefaultableService(
            $this->container,
            $this->queryClass,
            Query::class,
            [$this]
        );

        if (null !== $entity) {
            $queryInstance->entity($entity);
        }

        if (null !== $query) {
            $queryInstance->prepare($query);
        }

        return $queryInstance;
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
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = Cls::getDefaultableService(
            $this->container,
            $this->queryBuilderClass,
            QueryBuilder::class,
            [$this]
        );

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return Cls::getDefaultableService(
            $this->container,
            $this->retrieverClass,
            Retriever::class,
            [$this]
        );
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->persister
            ?? $this->persister = Cls::getDefaultableService(
                $this->container,
                $this->persisterClass,
                Persister::class,
                [$this]
            );
    }
}
