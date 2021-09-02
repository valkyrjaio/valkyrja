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

use PDO;
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\ORM\PDOAdapter as Contract;
use Valkyrja\ORM\Statement;
use Valkyrja\ORM\Statements\PDOStatement;
use Valkyrja\Support\Type\Cls;

use function is_bool;

/**
 * Class PDOAdapter.
 *
 * @author Melech Mizrachi
 */
class PDOAdapter extends Adapter implements Contract
{
    /**
     * The pdo service.
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * PDOAdapter constructor.
     *
     * @param Container $container The container
     * @param PDO       $pdo       The PDO
     * @param array     $config    The config
     */
    public function __construct(Container $container, PDO $pdo, array $config)
    {
        $this->pdo = $pdo;

        parent::__construct($container, $config);
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
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }
    }

    /**
     * Commit all items in the transaction.
     *
     *
     * @return bool
     */
    public function commit(): bool
    {
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
     * Rollback the previous transaction.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement
    {
        $statement = $this->pdo->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement preparation has failed.');
        }

        return Cls::getDefaultableService(
            $this->container,
            PDOStatement::class,
            \Valkyrja\ORM\PDOStatement::class,
            [$statement]
        );
    }

    /**
     * Get the last inserted id.
     *
     * @param string|null $table [optional] The table last inserted into
     * @param string|null $idField [optional] The id field of the table last inserted into
     *
     * @return string
     */
    public function lastInsertId(string $table = null, string $idField = null): string
    {
        $name = null;

        if ($this->config['config']['driver'] === 'pgsql' && $table && $idField) {
            $name = "{$table}_{$idField}_seq";
        }

        return (string) $this->pdo->lastInsertId($name);
    }
}
