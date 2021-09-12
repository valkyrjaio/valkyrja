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
 * Interface QueryFactory.
 *
 * @author Melech Mizrachi
 */
interface QueryFactory
{
    /**
     * Create a query.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The query class name
     *
     * @return T
     */
    public function createQuery(Adapter $adapter, string $name): Query;
}
