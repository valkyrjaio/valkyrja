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

namespace Valkyrja\Orm\Adapters;

use Valkyrja\Orm\Statement;
use Valkyrja\Orm\Statement\NullStatement;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter extends Adapter
{
    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function ensureTransaction(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): Statement
    {
        return $this->orm->createStatement($this, NullStatement::class);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        return '';
    }
}
