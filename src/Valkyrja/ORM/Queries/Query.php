<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Queries;

use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\Statement;

/**
 * Class Query.
 *
 * @author Melech Mizrachi
 */
class Query implements QueryContract
{
    /**
     * The connection.
     *
     * @var Connection
     */
    protected Connection $connection;

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
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
        $this->statement = $this->connection->prepare($query);

        if (null !== $this->table) {
            $this->bindValue('QueryTable', $this->table);
        }

        if (null !== $this->entity) {
            $this->bindValue($this->entity, $this->entity::getEntityTable());
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
     * @return mixed
     */
    public function getResult()
    {
        $results = $this->statement->fetchAll();

        // If there is no entity specified just return the results
        if (null === $this->entity) {
            return $results;
        }

        // If the result of the query was a count
        if (isset($results[0]['COUNT(*)'])) {
            return (int) $results[0]['COUNT(*)'];
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
