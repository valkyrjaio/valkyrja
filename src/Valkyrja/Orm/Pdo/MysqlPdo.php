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

namespace Valkyrja\Orm\Pdo;

use Valkyrja\Orm\Config\MysqlConnection;

/**
 * Class MysqlPdo.
 *
 * @author Melech Mizrachi
 */
class MysqlPdo extends Pdo
{
    /**
     * MySqlPDO constructor.
     */
    public function __construct(MysqlConnection $config)
    {
        $dsn = $this->getDsnPart($config, 'charset')
            . $this->getDsnPart($config, 'strict')
            . $this->getDsnPart($config, 'engine');

        parent::__construct($config, 'mysql', $dsn);
    }
}
