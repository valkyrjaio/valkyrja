<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Manager;

use Override;
use Valkyrja\Orm\Manager\Abstract\PdoManager;
use Valkyrja\Orm\Throwable\Exception\OrmNoPgsqlLastIdException;

use function is_string;

class PgsqlManager extends PdoManager
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function lastInsertId(string $table, string $idField): string
    {
        $name = "{$table}_{$idField}_seq";

        /** @var non-empty-string|false $lastInsertId */
        $lastInsertId = $this->pdo->lastInsertId($name);

        return is_string($lastInsertId)
            ? $lastInsertId
            : throw new OrmNoPgsqlLastIdException('No last insert id found');
    }
}
