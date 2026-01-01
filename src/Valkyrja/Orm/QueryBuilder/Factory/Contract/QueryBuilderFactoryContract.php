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

use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;

interface QueryBuilderFactoryContract
{
    /**
     * Create a select query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return SelectQueryBuilderContract
     */
    public function select(string $table): SelectQueryBuilderContract;

    /**
     * Create an insert query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return InsertQueryBuilderContract
     */
    public function insert(string $table): InsertQueryBuilderContract;

    /**
     * Create an update query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return UpdateQueryBuilderContract
     */
    public function update(string $table): UpdateQueryBuilderContract;

    /**
     * Create a delete query builder.
     *
     * @param non-empty-string $table The table
     *
     * @return DeleteQueryBuilderContract
     */
    public function delete(string $table): DeleteQueryBuilderContract;
}
