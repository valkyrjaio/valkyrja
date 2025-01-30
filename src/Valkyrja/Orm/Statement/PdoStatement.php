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

namespace Valkyrja\Orm\Statement;

use PDO;
use PDOStatement as Statement;
use stdClass;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Orm\Statement\Contract\PdoStatement as Contract;

use function is_array;
use function is_bool;
use function is_int;
use function is_object;

/**
 * Class PDOStatement.
 *
 * @author Melech Mizrachi
 */
class PdoStatement implements Contract
{
    /**
     * PDOStatement constructor.
     *
     * @param Statement $statement
     */
    public function __construct(
        protected Statement $statement
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bindValue(string $parameter, mixed $value): bool
    {
        return $this->statement->bindValue(
            $parameter,
            $value,
            $this->getBindValueType($value)
        );
    }

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        return $this->statement->execute();
    }

    /**
     * @inheritDoc
     */
    public function getColumnMeta(int $columnNumber): array
    {
        $columnMeta = $this->statement->getColumnMeta($columnNumber);

        if ($columnMeta === false) {
            throw new RuntimeException($this->errorMessage() ?? "Error occurred when getting column meta for column number $columnNumber");
        }

        return $columnMeta;
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        $fetch = $this->statement->fetch(PDO::FETCH_ASSOC);

        if (! is_array($fetch)) {
            throw new RuntimeException($this->errorMessage() ?? 'Error occurred when fetching');
        }

        return $fetch;
    }

    /**
     * @inheritDoc
     */
    public function fetchColumn(int $columnNumber = 0): mixed
    {
        return $this->statement->fetchColumn($columnNumber);
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritDoc
     */
    public function fetchObject(string $className = stdClass::class): object
    {
        $object = $this->statement->fetchObject($className);

        if (! is_object($object)) {
            throw new RuntimeException($this->errorMessage() ?? "Error occurred when fetching object of type $className");
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        return $this->statement->columnCount();
    }

    /**
     * @inheritDoc
     */
    public function errorCode(): string
    {
        return $this->statement->errorInfo()[0] ?? '00000';
    }

    /**
     * @inheritDoc
     */
    public function errorMessage(): string|null
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
    protected function getBindValueType(mixed $value): int
    {
        return match (true) {
            is_int($value)  => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            $value === null => PDO::PARAM_NULL,
            default         => PDO::PARAM_STR,
        };
    }
}
