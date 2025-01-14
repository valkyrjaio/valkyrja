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

namespace Valkyrja\Orm\QueryBuilder;

use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\BaseQueryBuilder as Contract;

use function assert;

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
     * @var class-string<Entity>|null
     */
    protected string|null $entity = null;

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
    public function table(string $table, string|null $alias = null): static
    {
        $this->table = $table . ' ' . ((string) $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function entity(string $entity, string|null $alias = null): static
    {
        assert(is_a($entity, Entity::class, true));

        $this->entity = $entity;

        $this->table($entity, $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createQuery(): Query
    {
        return $this->adapter->createQuery($this->getQueryString(), $this->entity);
    }

    /**
     * @inheritDoc
     */
    abstract public function getQueryString(): string;
}
