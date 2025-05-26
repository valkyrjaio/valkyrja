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

namespace Valkyrja\Orm\Config;

/**
 * Class Connections.
 *
 * @author Melech Mizrachi
 */
class Connections
{
    public function __construct(
        public MysqlConnection|null $mysql = null,
        public PgSqlConnection|null $pgsql = null,
    ) {
    }
}
