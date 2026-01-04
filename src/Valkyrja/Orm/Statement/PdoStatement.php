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

use Override;
use PDO;
use PDOStatement as Statement;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\Statement\Contract\StatementContract as Contract;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;

class PdoStatement implements Contract
{
    public function __construct(
        protected Statement $statement
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function bindValue(Value $value): bool
    {
        if ($value->value instanceof QueryBuilderContract) {
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
    #[Override]
    public function execute(): bool
    {
        return $this->statement->execute();
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
     *
     * @template T of EntityContract
     *
     * @param class-string<T>|null $entity The entity class name
     *
     * @return ($entity is class-string<T> ? T : array<string, mixed>)
     */
    #[Override]
    public function fetch(string|null $entity = null): EntityContract|array
    {
        /** @var array<string, mixed>|false $fetch */
        $fetch = $this->statement->fetch(PDO::FETCH_ASSOC);

        if (! is_array($fetch)) {
            throw new RuntimeException($this->errorMessage() ?? 'Error occurred when fetching');
        }

        if ($entity !== null) {
            /** @var T $entityClass */
            $entityClass = $entity::fromArray($fetch);

            return $entityClass;
        }

        return $fetch;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetchColumn(int $columnNumber = 0): mixed
    {
        return $this->statement->fetchColumn($columnNumber);
    }

    /**
     * @inheritDoc
     *
     * @template T of EntityContract
     *
     * @param class-string<T>|null $entity The entity class name
     *
     * @return ($entity is class-string<T> ? T[] : array<string, mixed>[])
     */
    #[Override]
    public function fetchAll(string|null $entity = null): array
    {
        /** @var array<string, mixed>[] $fetch */
        $fetch = $this->statement->fetchAll(PDO::FETCH_ASSOC);

        if ($entity !== null) {
            /** @var T[] $entities */
            $entities = $this->mapResultsToEntity($entity, $fetch);

            return $entities;
        }

        return $fetch;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCount(): int
    {
        $results = $this->statement->fetchAll();

        /** @var array<string, int|string|mixed>|null $firstResults */
        $firstResults = $results[0] ?? null;

        if ($firstResults === null) {
            return 0;
        }

        /** @var mixed $count */
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
    #[Override]
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function columnCount(): int
    {
        return $this->statement->columnCount();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function errorCode(): string
    {
        return $this->statement->errorInfo()[0] ?? '00000';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function errorMessage(): string|null
    {
        return $this->statement->errorInfo()[2] ?? null;
    }

    /**
     * Get value type to bind with.
     *
     *
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

    /**
     * @template T of EntityContract
     *
     * @param class-string<T>        $entity  The entity class name
     * @param array<string, mixed>[] $results The results
     *
     * @return T[]
     */
    protected function mapResultsToEntity(string $entity, array $results): array
    {
        return array_map(
            static fn (array $data): EntityContract => $entity::fromArray($data),
            $results
        );
    }
}
