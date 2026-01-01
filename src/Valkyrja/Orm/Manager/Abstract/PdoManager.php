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

namespace Valkyrja\Orm\Manager\Abstract;

use Override;
use PDO;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract as Contract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository as DefaultRepository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\PdoStatement;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function is_bool;
use function is_string;

/**
 * Abtract Class PdoManager.
 *
 * @author Melech Mizrachi
 */
abstract class PdoManager implements Contract
{
    public function __construct(
        protected PDO $pdo,
        protected ContainerContract $container,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity
     *
     * @return RepositoryContract<T>
     */
    #[Override]
    public function createRepository(string $entity): RepositoryContract
    {
        $repositoryClass = $entity::getRepository()
            ?? DefaultRepository::class;

        /** @var RepositoryContract<T> $repository */
        $repository = $this->container->get($repositoryClass, [$this, $entity]);

        return $repository;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createQueryBuilder(): QueryBuilderFactoryContract
    {
        return new SqlQueryBuilderFactory();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ensureTransaction(): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function prepare(string $query): StatementContract
    {
        $statement = $this->pdo->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement preparation has failed');
        }

        return new PdoStatement(statement: $statement);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function query(string $query): StatementContract
    {
        $statement = $this->pdo->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement query has failed');
        }

        return new PdoStatement(statement: $statement);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        /** @var non-empty-string|false $lastInsertId */
        $lastInsertId = $this->pdo->lastInsertId();

        return is_string($lastInsertId)
            ? $lastInsertId
            : throw new RuntimeException('No last insert id found');
    }
}
