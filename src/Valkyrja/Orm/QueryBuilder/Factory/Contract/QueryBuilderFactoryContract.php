<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
     */
    public function select(string $table): SelectQueryBuilderContract;

    /**
     * Create an insert query builder.
     *
     * @param non-empty-string $table The table
     */
    public function insert(string $table): InsertQueryBuilderContract;

    /**
     * Create an update query builder.
     *
     * @param non-empty-string $table The table
     */
    public function update(string $table): UpdateQueryBuilderContract;

    /**
     * Create a delete query builder.
     *
     * @param non-empty-string $table The table
     */
    public function delete(string $table): DeleteQueryBuilderContract;
}
