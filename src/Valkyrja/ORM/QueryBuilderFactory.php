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

namespace Valkyrja\ORM;

/**
 * Interface QueryBuilderFactory.
 *
 * @author Melech Mizrachi
 */
interface QueryBuilderFactory
{
    /**
     * Create a query builder.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The query builder class name
     *
     * @return T
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder;
}
