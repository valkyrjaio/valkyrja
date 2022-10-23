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

namespace Valkyrja\ORM\QueryBuilders;

use Valkyrja\ORM\Constants\Statement;
use Valkyrja\ORM\QueryBuilders\Traits\Join;
use Valkyrja\ORM\QueryBuilders\Traits\Set;
use Valkyrja\ORM\QueryBuilders\Traits\Where;
use Valkyrja\ORM\UpdateQueryBuilder as Contract;

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