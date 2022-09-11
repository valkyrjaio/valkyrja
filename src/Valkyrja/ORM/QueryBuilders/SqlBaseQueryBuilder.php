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

namespace Valkyrja\ORM\QueryBuilders;

use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\BaseQueryBuilder as Contract;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query;

/**
 * Abstract Class SqlBaseQueryBuilder.
 *
 * @author Melech Mizrachi
 */
abstract class SqlBaseQueryBuilder implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The table upon which the statement executes.
     *
     * @var string
     */
    protected string $table;

    /**
     * The entity to query with.
     *
     * @var Entity|string|null
     */
    protected ?string $entity = null;

    /**
     * SqlBaseQueryBuilder constructor.
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     */
    public function table(string $table, string $alias = null): static
    {
        $this->table = $table . ' ' . ((string) $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function entity(string $entity, string $alias = null): static
    {
        $this->entity = $entity;

        $this->table($entity, $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function getQueryString(): string;

    /**
     * @inheritDoc
     */
    public function createQuery(): Query
    {
        return $this->adapter->createQuery($this->getQueryString(), $this->entity);
    }
}