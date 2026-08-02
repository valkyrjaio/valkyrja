<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\QueryBuilder;

use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\QueryBuilder\Abstract\SqlQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;

class SqlDeleteQueryBuilder extends SqlQueryBuilder implements DeleteQueryBuilderContract
{
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return Statement::DELETE
            . ' ' . Statement::FROM
            . " $this->from"
            . $this->getAliasQuery()
            . $this->getWhereQuery()
            . $this->getJoinQuery();
    }
}
