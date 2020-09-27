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

namespace Valkyrja\ORM\Statements;

use PDO;
use PDOStatement as Statement;
use Valkyrja\ORM\Statement as Contract;

use function is_bool;
use function is_int;

/**
 * Class PDOStatement.
 *
 * @author Melech Mizrachi
 */
class PDOStatement implements Contract
{
    /**
     * The PDO Statement.
     *
     * @var Statement
     */
    protected Statement $statement;

    /**
     * PDOConnection constructor.
     *
     * @param Statement $statement
     */
    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Bind a value.
     *
     * @param string $parameter
     * @param mixed  $value
     *
     * @return bool
     */
    public function bindValue(string $parameter, $value): bool
    {
        return $this->statement->bindValue(
            $parameter,
            $value,
            $this->getBindValueType($value)
        );
    }

    /**
     * Execute the statement.
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->statement->execute();
    }

    /**
     * Get a column's meta information.
     *
     * @param int $columnNumber The column index in relation to the query statement
     *
     * @return array
     */
    public function getColumnMeta(int $columnNumber): array
    {
        return $this->statement->getColumnMeta($columnNumber);
    }

    /**
     * Fetch the results.
     *
     * @return array
     */
    public function fetch(): array
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single column.
     *
     * @param int $columnNumber
     *
     * @return mixed
     */
    public function fetchColumn(int $columnNumber = 0)
    {
        return $this->statement->fetchColumn($columnNumber);
    }

    /**
     * Fetch all the results.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the results as an object.
     *
     * @param string $className
     *
     * @return object
     */
    public function fetchObject(string $className = 'stdClass'): object
    {
        return $this->statement->fetchObject($className);
    }

    /**
     * The number of rows returned.
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * Count of columns returned.
     *
     * @return int
     */
    public function columnCount(): int
    {
        return $this->statement->columnCount();
    }

    /**
     * The error code.
     *
     * @return string
     */
    public function errorCode(): string
    {
        return $this->statement->errorInfo()[0] ?? '00000';
    }

    /**
     * The error message.
     *
     * @return string|null
     */
    public function errorMessage(): ?string
    {
        return $this->statement->errorInfo()[2] ?? null;
    }

    /**
     * Get value type to bind with.
     *
     * @param mixed $value
     *
     * @return int
     */
    protected function getBindValueType($value): int
    {
        $type = PDO::PARAM_STR;

        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        }

        return $type;
    }
}
