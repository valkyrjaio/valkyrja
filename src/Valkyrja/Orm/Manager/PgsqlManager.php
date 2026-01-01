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

namespace Valkyrja\Orm\Manager;

use Override;
use Valkyrja\Orm\Manager\Abstract\PdoManager;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function is_string;

class PgsqlManager extends PdoManager
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        $name = null;

        if ($table !== null && $idField !== null) {
            $name = "{$table}_{$idField}_seq";
        }

        /** @var non-empty-string|false $lastInsertId */
        $lastInsertId = $this->pdo->lastInsertId($name);

        return is_string($lastInsertId)
            ? $lastInsertId
            : throw new RuntimeException('No last insert id found');
    }
}
