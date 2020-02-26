<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use PDO;

/**
 * Interface PDOConnection.
 *
 * @author Melech Mizrachi
 */
interface PDOConnection extends Connection
{
    /**
     * The PDO.
     *
     * @return PDO
     */
    public function pdo(): PDO;
}
