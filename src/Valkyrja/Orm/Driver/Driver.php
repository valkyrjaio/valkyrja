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

namespace Valkyrja\Orm\Driver;

use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Driver\Contract\Driver as Contract;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Retriever\Contract\Retriever;
use Valkyrja\Orm\Statement\Contract\Statement;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter              $adapter The adapter
     * @param array<string, mixed> $config  The config
     */
    public function __construct(
        protected Adapter $adapter,
        protected array $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return $this->adapter->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        return $this->adapter->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function ensureTransaction(): void
    {
        $this->adapter->ensureTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return $this->adapter->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return $this->adapter->rollback();
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): Statement
    {
        return $this->adapter->prepare($query);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        return $this->adapter->lastInsertId($table, $idField);
    }

    /**
     * @inheritDoc
     */
    public function createQuery(string|null $query = null, string|null $entity = null): Query
    {
        return $this->adapter->createQuery($query, $entity);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(string|null $entity = null, string|null $alias = null): QueryBuilder
    {
        return $this->adapter->createQueryBuilder($entity, $alias);
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(): Retriever
    {
        return $this->adapter->createRetriever();
    }

    /**
     * @inheritDoc
     */
    public function getPersister(): Persister
    {
        return $this->adapter->getPersister();
    }
}
