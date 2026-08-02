<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Orm;

use PDO;
use PDOStatement;

/**
 * PDO test class.
 */
final class PdoFixture extends PDO
{
    public function query(string $query, int|null $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false
    {
        return false;
    }
}
