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

namespace Valkyrja\Orm\Statement;

use Override;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Statement\Contract\Statement as Contract;

/**
 * Class NullStatement.
 *
 * @author Melech Mizrachi
 */
class NullStatement implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function bindValue(Value $value): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function execute(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getColumnMeta(int $columnNumber): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetch(string|null $entity = null): Entity|array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetchColumn(int $columnNumber = 0): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetchAll(string|null $entity = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rowCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function columnCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function errorCode(): string
    {
        return '00000';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function errorMessage(): string|null
    {
        return null;
    }
}
