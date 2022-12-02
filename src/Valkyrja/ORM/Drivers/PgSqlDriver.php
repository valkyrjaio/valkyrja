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

namespace Valkyrja\ORM\Drivers;

use Valkyrja\ORM\Adapter;

/**
 * Class PgSqlDriver.
 *
 * @author Melech Mizrachi
 */
class PgSqlDriver extends Driver
{
    /**
     * PgSqlDriver constructor.
     *
     * @param Adapter $adapter The adapter
     * @param array   $config  The config
     */
    public function __construct(Adapter $adapter, array $config)
    {
        parent::__construct($adapter, $config);

        $schema = $config['schema'] ?? null;

        if ($schema && $statement = $this->prepare("set search_path to $schema")) {
            $statement->execute();
        }
    }
}
