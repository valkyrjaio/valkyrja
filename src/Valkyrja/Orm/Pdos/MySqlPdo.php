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

namespace Valkyrja\Orm\Pdos;

/**
 * Class MySqlPDO.
 *
 * @author Melech Mizrachi
 */
class MySqlPdo extends Pdo
{
    /**
     * MySqlPDO constructor.
     *
     * @param array $config The config
     */
    public function __construct(array $config)
    {
        $dsn = $this->getDsnPart($config, 'charset')
            . $this->getDsnPart($config, 'strict')
            . $this->getDsnPart($config, 'engine');

        parent::__construct($config, 'mysql', $dsn);
    }
}
