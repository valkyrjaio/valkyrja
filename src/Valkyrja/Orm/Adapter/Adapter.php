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

namespace Valkyrja\Orm\Adapter;

use Valkyrja\Orm\Adapter\Contract\Adapter as Contract;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Retriever\Contract\Retriever;

/**
 * Abstract Class Adapter.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type Config array{query: class-string<Query>, queryBuilder: class-string<QueryBuilder>, persister: class-string<Persister>, retriever: class-string<Retriever>, ...}
 *
 * @phpstan-type Config array{query: class-string<Query>, queryBuilder: class-string<QueryBuilder>, persister: class-string<Persister>, retriever: class-string<Retriever>, ...}
 */
abstract class Adapter implements Contract
{
    /**
     * The entity persister.
     *
     * @var Persister<Entity>
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
     * Adapter constructor.
     *
     * @param Orm    $orm    The orm
     * @param Config $config The config
     */
    public function __construct(
        protected Orm $orm,
        protected array $config
    ) {
        $this->queryClass        = $config['query'];
        $this->queryBuilderClass = $config['queryBuilder'];
        $this->persisterClass    = $config['persister'];
        $this->retrieverClass    = $config['retriever'];
    }

    /**
     * @inheritDoc
     */
    public function createQuery(?string $query = null, ?string $entity = null): Query
    {
        $queryInstance = $this->orm->createQuery($this, $this->queryClass);

        if ($entity !== null) {
            $queryInstance->entity($entity);
        }

        if ($query !== null) {
            $queryInstance->prepare($query);
        }

        return $queryInstance;
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(?string $entity = null, ?string $alias = null): QueryBuilder
    {
        $queryBuilder = $this->orm->createQueryBuilder($this, $this->queryBuilderClass);

        if ($entity !== null) {
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
            ??= $this->orm->createPersister($this, $this->persisterClass);
    }
}
