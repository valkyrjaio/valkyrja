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
     * The connection.
     *
     * @param string $connection The connection to use
     *
     * @return Connection
     */
    public function connection(string $connection): Connection;

    /**
     * The query builder.
     *
     * @return QueryBuilder
     */
    public function queryBuilder(): QueryBuilder;
}
