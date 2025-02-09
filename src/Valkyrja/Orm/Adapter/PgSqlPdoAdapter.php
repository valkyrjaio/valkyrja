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

namespace Valkyrja\Orm\Adapter;

use Valkyrja\Exception\RuntimeException;

use function is_string;

/**
 * Class PgSqlPdoAdapter.
 *
 * @author Melech Mizrachi
 */
class PgSqlPdoAdapter extends PdoAdapter
{
    /**
     * @inheritDoc
     */
    public function lastInsertId(?string $table = null, ?string $idField = null): string
    {
        $name = null;

        if ($table !== null && $table !== '' && $idField !== null && $idField !== '') {
            $name = "{$table}_{$idField}_seq";
        }

        /** @var string|false $lastInsertId */
        $lastInsertId = $this->pdo->lastInsertId($name);

        return is_string($lastInsertId)
            ? $lastInsertId
            : throw new RuntimeException('No last insert id found');
    }
}
