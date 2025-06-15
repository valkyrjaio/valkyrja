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

namespace Valkyrja\Orm\Driver;

use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Config\PgsqlConnection;

/**
 * Class PgsqlDriver.
 *
 * @author Melech Mizrachi
 */
class PgsqlDriver extends Driver
{
    /**
     * PgSqlDriver constructor.
     */
    public function __construct(Adapter $adapter, PgsqlConnection $config)
    {
        parent::__construct($adapter, $config);

        $schema = $config->schema;

        $statement = $this->prepare("set search_path to $schema");
        $statement->execute();
    }
}
