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

namespace Valkyrja\Orm\QueryBuilder\Factory\Contract;

use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder;

/**
 * Interface QueryBuilderFactory.
 *
 * @author Melech Mizrachi
 */
interface QueryBuilderFactory
{
    /**
     * Create a select query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return SelectQueryBuilder
     */
    public function select(string $table): SelectQueryBuilder;

    /**
     * Create an insert query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return InsertQueryBuilder
     */
    public function insert(string $table): InsertQueryBuilder;

    /**
     * Create an update query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return UpdateQueryBuilder
     */
    public function update(string $table): UpdateQueryBuilder;

    /**
     * Create a delete query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return DeleteQueryBuilder
     */
    public function delete(string $table): DeleteQueryBuilder;
}
