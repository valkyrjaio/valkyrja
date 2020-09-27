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

namespace Valkyrja\ORM\Queries;

use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\NotFoundException;
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\Statement;
use Valkyrja\Support\Type\Str;

use function is_array;

/**
 * Class Query.
 *
 * @author Melech Mizrachi
 */
class Query implements QueryContract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

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
    protected ?string $table = null;

    /**
     * The entity to query with.
     *
     * @var Entity|string|null
     */
    protected ?string $entity = null;

    /**
     * PDOQuery constructor.
     *
     * @param Adapter $connection
     */
    public function __construct(Adapter $connection)
    {
        $this->adapter = $connection;
    }

    /**
     * Set the table to query on.
     *
     * @param string $table
     *
     * @return static
     */
    public function table(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Set the entity to query with.
     *
     * @param string $entity
     *
     * @return static
     */
    public function entity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Prepare the query.
     *
     * @param string $query
     *
     * @return static
     */
    public function prepare(string $query): self
    {
        if (null !== $this->entity) {
            $query = Str::replace($query, $this->entity, $this->entity::getTableName());
        }

        $this->statement = $this->adapter->prepare($query);

        if (null !== $this->table) {
            $this->bindValue('table', $this->table);
        }

        return $this;
    }

    /**
     * Bind a value.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return static
     */
    public function bindValue(string $property, $value): self
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $this->bindValue($property . $key, $item);
            }

            return $this;
        }

        // And bind each value to the column
        $this->statement->bindValue($this->propertyBind($property), $value);

        return $this;
    }

    /**
     * Execute the query.
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->statement->execute();
    }

    /**
     * Get the result(s).
     *
     * @return Entity[]|object[]
     */
    public function getResult(): array
    {
        $results = $this->statement->fetchAll();

        // If there is no entity specified just return the results
        if (null === $this->entity) {
            foreach ($results as &$result) {
                $result = (object) $result;
            }

            unset($result);

            return $results;
        }

        $entities = [];

        // Iterate through the rows found
        foreach ($results as $result) {
            // Create a new entity
            /** @var Entity $entity */
            $entities[] = $this->entity::fromArray($result);
        }

        return $entities;
    }

    /**
     * Get one or null.
     *
     * @return Entity|object|null
     */
    public function getOneOrNull(): ?object
    {
        return $this->getResult()[0] ?? null;
    }

    /**
     * Get one or fail.
     *
     * @throws NotFoundException
     *
     * @return Entity|object
     */
    public function getOneOrFail(): object
    {
        $results = $this->getOneOrNull();

        if (null === $results) {
            throw new NotFoundException('Result Not Found');
        }

        return $results;
    }

    /**
     * Get count results.
     *
     * @return int
     */
    public function getCount(): int
    {
        $results = $this->statement->fetchAll();

        return (int) ($results[0]['COUNT(*)'] ?? 0);
    }

    /**
     * Get the error if one occurred.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->statement->errorMessage() ?? 'An unknown error occurred.';
    }

    /**
     * Get a property name to bind a value to.
     *
     * @param string $property
     *
     * @return string
     */
    protected function propertyBind(string $property): string
    {
        return ':' . $property;
    }
}
