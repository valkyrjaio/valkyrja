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

namespace Valkyrja\Tests\Classes\Orm;

use PDO;
use PDOStatement;

/**
 * PDO test class.
 *
 * @author Melech Mizrachi
 */
class PdoClass extends PDO
{
    public function query(string $query, int|null $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false
    {
        return false;
    }
}
