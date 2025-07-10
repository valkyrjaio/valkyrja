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
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactory as Contract;
use Valkyrja\Orm\QueryBuilder\SqlDeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlInsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlSelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlUpdateQueryBuilder;

/**
 * Class SqlQueryBuilderFactory.
 *
 * @author Melech Mizrachi
 */
class SqlQueryBuilderFactory implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function select(string $table): SelectQueryBuilder
    {
        return new SqlSelectQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(string $table): InsertQueryBuilder
    {
        return new SqlInsertQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(string $table): UpdateQueryBuilder
    {
        return new SqlUpdateQueryBuilder(from: $table);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(string $table): DeleteQueryBuilder
    {
        return new SqlDeleteQueryBuilder(from: $table);
    }
}
