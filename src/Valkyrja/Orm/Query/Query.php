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

namespace Valkyrja\Orm\Query;

use stdClass;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Exception\NotFoundException;
use Valkyrja\Orm\Query\Contract\Query as QueryContract;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\Orm\Support\Helpers;

use function assert;
use function is_array;
use function is_int;
use function is_string;

/**
 * Class Query.
 *
 * @author Melech Mizrachi
 */
class Query implements QueryContract
{
    /**
     * The statement.
     *
     * @var Statement
     */
    protected Statement $statement;

    /**
     * The table to query on.
     *
     * @var string|null
     */
    protected string|null $table = null;

    /**
     * The entity to query with.
     *
     * @var class-string<Entity>|null
     */
    protected string|null $entity = null;

    /**
     * Query constructor.
     *
     * @param Adapter $adapter
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function table(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function entity(string $entity): static
    {
        assert(is_a($entity, Entity::class, true));

        $this->entity = $entity;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): static
    {
        $entity = $this->entity;

        if ($entity !== null) {
            $query = str_replace($entity, $entity::getTableName(), $query);
        }

        $this->statement = $this->adapter->prepare($query);

        if ($this->table !== null) {
            $this->bindValue('table', $this->table);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindValue(string $property, array|string|float|int|bool|null $value): static
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $this->bindValue($property . ((string) $key), $item);
            }

            return $this;
        }

        // And bind each value to the column
        $this->statement->bindValue(Helpers::getColumnForValueBind($property), $value);

        return $this;
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
    public function getResult(): array
    {
        $results = $this->statement->fetchAll();

        /** @var class-string<Entity>|null $entity */
        $entity = $this->entity;

        // If there is no entity specified just return the results
        if ($entity === null) {
            return array_map(
                static function (array $data): stdClass {
                    /** @var stdClass $object */
                    $object = (object) $data;

                    return $object;
                },
                $results
            );
        }

        return array_map(
            static fn (array $data): Entity => $entity::fromArray($data),
            $results
        );
    }

    /**
     * @inheritDoc
     */
    public function getOneOrNull(): Entity|stdClass|null
    {
        return $this->getResult()[0] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getOneOrFail(): object
    {
        $results = $this->getOneOrNull();

        if ($results === null) {
            throw new NotFoundException('Result Not Found');
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        $results = $this->statement->fetchAll();

        $count = ($results[0]['COUNT(*)'] ?? $results[0]['count'] ?? 0);

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
    public function getError(): string
    {
        return $this->statement->errorMessage() ?? 'An unknown error occurred.';
    }
}
