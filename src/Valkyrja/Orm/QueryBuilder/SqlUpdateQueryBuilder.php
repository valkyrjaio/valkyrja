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
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder as Contract;
use Valkyrja\Orm\QueryBuilder\Traits\Join;
use Valkyrja\Orm\QueryBuilder\Traits\Set;
use Valkyrja\Orm\QueryBuilder\Traits\Where;

/**
 * Class SqlUpdateQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlUpdateQueryBuilder extends SqlBaseQueryBuilder implements Contract
{
    use Join;
    use Set;
    use Where;

    /**
     * @inheritDoc
     */
    public function getQueryString(): string
    {
        return Statement::UPDATE
            . ' ' . $this->table
            . ' ' . $this->getSetQuery()
            . ' ' . $this->getWhereQuery()
            . ' ' . $this->getJoinQuery();
    }
}
