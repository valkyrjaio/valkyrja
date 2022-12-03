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

use Valkyrja\ORM\Adapter as Contract;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever;

/**
 * Abstract Class Adapter.
 *
 * @author Melech Mizrachi
 */
abstract class Adapter implements Contract
{
    /**
     * The ORM service.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * The entity persister.
     *
     * @var Persister
     */
    protected Persister $persister;

    /**
     * The query service to use.
     *
     * @var class-string<Query>
     */
    protected string $queryClass;

    /**
     * The query builder service to use.
     *
     * @var class-string<QueryBuilder>
     */
    protected string $queryBuilderClass;

    /**
     * The persister service to use.
     *
     * @var class-string<Persister>
     */
    protected string $persisterClass;

    /**
     * The retriever service to use.
     *
     * @var class-string<Retriever>
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
     * @param ORM   $orm    The orm
     * @param array $config The config
     */
    public function __construct(ORM $orm, array $config)
    {
        $this->orm               = $orm;
        $this->config            = $config;
        $this->queryClass        = $this->config['query'];
        $this->queryBuilderClass = $this->config['queryBuilder'];
        $this->persisterClass    = $this->config['persister'];
        $this->retrieverClass    = $this->config['retriever'];
    }

    /**
     * @inheritDoc
     */
    public function createQuery(string $query = null, string $entity = null): Query
    {
        $queryInstance = $this->orm->createQuery($this, $this->queryClass);

        if (null !== $entity) {
            $queryInstance->entity($entity);
        }

        if (null !== $query) {
            $queryInstance->prepare($query);
        }

        return $queryInstance;
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        $queryBuilder = $this->orm->createQueryBuilder($this, $this->queryBuilderClass);

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(): Retriever
    {
        return $this->orm->createRetriever($this, $this->retrieverClass);
    }

    /**
     * @inheritDoc
     */
    public function getPersister(): Persister
    {
        return $this->persister
            ?? $this->persister = $this->orm->createPersister($this, $this->persisterClass);
    }
}
