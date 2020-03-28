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
     * Get the PDO.
     *
     * @return PDO
     */
    public function getPDO(): PDO;
}
