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

namespace Valkyrja\ORM\Adapters;

use Valkyrja\Application\Application;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Connections\PDOConnection;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilders\SqlQueryBuilder;

/**
 * Class PDOAdapter.
 *
 * @author Melech Mizrachi
 */
class PDOAdapter implements Adapter
{
    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The entity manager.
     *
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * PDOAdapter constructor.
     *
     * @param Application   $app
     * @param EntityManager $entityManager
     */
    public function __construct(Application $app, EntityManager $entityManager)
    {
        $this->app           = $app;
        $this->entityManager = $entityManager;
    }

    /**
     * Make a new adapter.
     *
     * @param Application   $app
     * @param EntityManager $entityManager
     *
     * @return static
     */
    public static function make(Application $app, EntityManager $entityManager): self
    {
        return new static($app, $entityManager);
    }

    /**
     * Create a new connection.
     *
     * @param string $connection The connection to use
     *
     * @return Connection
     */
    public function createConnection(string $connection): Connection
    {
        return new PDOConnection($this->app, $connection);
    }

    /**
     * Create a new query builder.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        $queryBuilder = new SqlQueryBuilder($this->entityManager);

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }
}