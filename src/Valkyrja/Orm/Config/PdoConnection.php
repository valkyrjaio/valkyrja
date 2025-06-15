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

use Valkyrja\Orm\Pdo\Pdo;

/**
 * Abstract Class PdoConnection.
 *
 * @author Melech Mizrachi
 */
abstract class PdoConnection extends Connection
{
    /**
     * @param class-string<Pdo>    $pdoClass
     * @param array<int, int|bool> $options
     */
    public function __construct(
        public string $pdoClass,
        public string $pdoDriver,
        public string $host = '127.0.0.1',
        public string $port = '3306',
        public string $db = 'valkyrja',
        public string $user = 'valkyrja',
        public string $password = '',
        public string $charset = 'utf8',
        public array|null $options = null,
    ) {
        parent::__construct();
    }
}
