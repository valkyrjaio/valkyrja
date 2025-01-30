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

use PDO;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Orm\Adapter\Contract\PdoAdapter as Contract;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\Orm\Statement\PdoStatement;

use function is_bool;
use function is_string;

/**
 * Class PdoAdapter.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type Config from Adapter
 *
 * @phpstan-import-type Config from Adapter
 */
class PdoAdapter extends Adapter implements Contract
{
    /**
     * PDOAdapter constructor.
     *
     * @param Orm    $orm    The orm
     * @param PDO    $pdo    The PDO
     * @param Config $config The config
     */
    public function __construct(
        Orm $orm,
        protected PDO $pdo,
        array $config
    ) {
        parent::__construct($orm, $config);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function ensureTransaction(): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): Statement
    {
        $statement = $this->pdo->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement preparation has failed.');
        }

        return $this->orm->createStatement($this, PdoStatement::class, [$statement]);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        /** @var string|false $lastInsertId */
        $lastInsertId = $this->pdo->lastInsertId();

        return is_string($lastInsertId)
            ? $lastInsertId
            : throw new RuntimeException('No last insert id found');
    }
}
