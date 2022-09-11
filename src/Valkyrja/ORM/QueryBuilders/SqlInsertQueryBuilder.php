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
use Valkyrja\ORM\InsertQueryBuilder as Contract;
use Valkyrja\ORM\QueryBuilders\Traits\Join;
use Valkyrja\ORM\QueryBuilders\Traits\Set;

use function array_keys;
use function implode;

/**
 * Class SqlInsertQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlInsertQueryBuilder extends SqlBaseQueryBuilder implements Contract
{
    use Join;
    use Set;

    /**
     * @inheritDoc
     */
    public function getQueryString(): string
    {
        return Statement::INSERT
            . ' ' . Statement::INTO
            . ' ' . $this->table
            . ' (' . implode(', ', array_keys($this->values)) . ')'
            . ' ' . Statement::VALUES
            . ' (' . implode(', ', $this->values) . ')'
            . ' ' . $this->getJoinQuery();
    }
}
