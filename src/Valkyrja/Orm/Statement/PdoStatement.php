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
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Exception\RuntimeException;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Statement\Contract\Statement as Contract;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;

/**
 * Class PdoStatement.
 *
 * @author Melech Mizrachi
 */
class PdoStatement implements Contract
{
    /**
     * PdoStatement constructor.
     *
     * @param Statement $statement The pdo statement
     */
    public function __construct(
        protected Statement $statement
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bindValue(Value $value): bool
    {
        if ($value->value instanceof QueryBuilder) {
            return true;
        }

        if (is_array($value->value)) {
            $ret = false;

            foreach ($value->value as $key => $item) {
                $ret = $this->statement->bindValue(
                    param: ":$value->name$key",
                    value: $item,
                    type: $this->getBindValueType($item)
                );
            }

            return $ret;
        }

        return $this->statement->bindValue(
            param: ":$value->name",
            value: $value->value,
            type: $this->getBindValueType($value->value)
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
        /** @var array<string, mixed>|false $columnMeta */
        $columnMeta = $this->statement->getColumnMeta($columnNumber);

        if ($columnMeta === false) {
            throw new RuntimeException(
                $this->errorMessage()
                ?? "Error occurred when getting column meta for column number $columnNumber"
            );
        }

        return $columnMeta;
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        /** @var array<string, mixed>|false $fetch */
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
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function fetchAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        $results = $this->statement->fetchAll();

        $firstResults = $results[0] ?? null;

        if ($firstResults === null) {
            return 0;
        }

        /** @var array<string, int|string|mixed> $firstResults */
        $count = $firstResults['COUNT(*)']
            ?? $firstResults['count']
            ?? 0;

        if (is_int($count)) {
            return $count;
        }

        if (is_string($count)) {
            return (int) $count;
        }

        throw new RuntimeException('Unsupported count results');
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
