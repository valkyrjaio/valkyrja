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
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;

class PdoStatement implements StatementContract
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
                $this->hasError()
                    ? $this->getErrorMessage()
                    : "Error occurred when getting column meta for column number $columnNumber"
            );
        }

        return $columnMeta;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetch(): array
    {
        /** @var array<string, mixed>|false $fetch */
        $fetch = $this->statement->fetch(PDO::FETCH_ASSOC);

        if (! is_array($fetch)) {
            throw new RuntimeException(
                $this->hasError()
                    ? $this->getErrorMessage()
                    : 'Error occurred when fetching'
            );
        }

        return $fetch;
    }

    /**
     * @inheritDoc
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity class name
     *
     * @return T
     */
    #[Override]
    public function fetchEntity(string $entity): EntityContract
    {
        /** @var T $entityClass */
        $entityClass = $entity::fromArray($this->fetch());

        return $entityClass;
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
     */
    #[Override]
    public function fetchAll(): array
    {
        /** @var array<string, mixed>[] $fetch */
        $fetch = $this->statement->fetchAll(PDO::FETCH_ASSOC);

        return $fetch;
    }

    /**
     * @inheritDoc
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity class name
     *
     * @return T[]
     */
    #[Override]
    public function fetchAllEntities(string $entity): array
    {
        /** @var T[] $entities */
        $entities = $this->mapResultsToEntity($entity, $this->fetchAll());

        return $entities;
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

        /** @var scalar $count */
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
    public function getRowCount(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getColumnCount(): int
    {
        return $this->statement->columnCount();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasError(): bool
    {
        return $this->getErrorCode() !== '00000';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getErrorCode(): string
    {
        return $this->statement->errorInfo()[0] ?? '00000';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getErrorMessage(): string
    {
        return $this->statement->errorInfo()[2] ?? '';
    }

    /**
     * Get value type to bind with.
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
