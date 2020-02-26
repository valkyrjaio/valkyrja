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

namespace Valkyrja\ORM;

use Valkyrja\Application\Application;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Make a new adapter.
     *
     * @param Application   $app
     * @param EntityManager $entityManager
     *
     * @return static
     */
    public static function make(Application $app, EntityManager $entityManager): self;

    /**
     * Create a new connection.
     *
     * @param string $connection The connection to use
     *
     * @return Connection
     */
    public function createConnection(string $connection): Connection;

    /**
     * Create a new query builder.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder;
}
