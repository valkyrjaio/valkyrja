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

namespace Valkyrja\Orm\QueryBuilder;

use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilder as Contract;

/**
 * Class SqlDeleteQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlDeleteQueryBuilder extends SqlQueryBuilder implements Contract
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
