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
 * Interface StatementFactory.
 *
 * @author Melech Mizrachi
 */
interface StatementFactory
{
    /**
     * Create a statement.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The statement class name
     * @param array           $data    [optional] Additional data required for the statement
     *
     * @return T
     */
    public function createStatement(Adapter $adapter, string $name, array $data = []): Statement;
}
