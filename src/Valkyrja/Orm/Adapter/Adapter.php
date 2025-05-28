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
use Valkyrja\Orm\Config\Connection;
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
     * Adapter constructor.
     */
    public function __construct(
        protected Orm $orm,
        protected Connection $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createQuery(string|null $query = null, string|null $entity = null): Query
    {
        $queryInstance = $this->orm->createQuery($this, $this->config->queryClass);

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
    public function createQueryBuilder(string|null $entity = null, string|null $alias = null): QueryBuilder
    {
        $queryBuilder = $this->orm->createQueryBuilder($this, $this->config->queryBuilderClass);

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
        return $this->orm->createRetriever($this, $this->config->retrieverClass);
    }

    /**
     * @inheritDoc
     */
    public function getPersister(): Persister
    {
        return $this->persister
            ??= $this->orm->createPersister($this, $this->config->persisterClass);
    }
}
