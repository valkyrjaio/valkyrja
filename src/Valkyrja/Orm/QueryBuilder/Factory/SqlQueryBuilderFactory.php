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

namespace Valkyrja\Orm\QueryBuilder\Factory;

use Override;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\SqlDeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlInsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlSelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlUpdateQueryBuilder;

class SqlQueryBuilderFactory implements QueryBuilderFactoryContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function select(string $table): SelectQueryBuilderContract
    {
        return new SqlSelectQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(string $table): InsertQueryBuilderContract
    {
        return new SqlInsertQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(string $table): UpdateQueryBuilderContract
    {
        return new SqlUpdateQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(string $table): DeleteQueryBuilderContract
    {
        return new SqlDeleteQueryBuilder(from: $table);
    }
}
