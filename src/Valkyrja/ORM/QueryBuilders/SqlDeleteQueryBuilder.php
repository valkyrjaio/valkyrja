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
use Valkyrja\ORM\DeleteQueryBuilder as Contract;
use Valkyrja\ORM\QueryBuilders\Traits\Join;
use Valkyrja\ORM\QueryBuilders\Traits\Where;

/**
 * Class SqlDeleteQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlDeleteQueryBuilder extends SqlBaseQueryBuilder implements Contract
{
    use Join;
    use Where;

    /**
     * @inheritDoc
     */
    public function getQueryString(): string
    {
        return Statement::DELETE
            . ' ' . Statement::FROM
            . ' ' . $this->table
            . ' ' . $this->getWhereQuery()
            . ' ' . $this->getJoinQuery();
    }
}
