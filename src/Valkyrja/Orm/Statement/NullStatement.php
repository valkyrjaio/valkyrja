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
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Statement\Contract\StatementContract;

class NullStatement implements StatementContract
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
    public function fetch(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetchEntity(string $entity): EntityContract
    {
        return new $entity();
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
    public function fetchAll(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function fetchAllEntities(string $entity): array
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
    public function getRowCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getColumnCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasError(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getErrorCode(): string
    {
        return '00000';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getErrorMessage(): string
    {
        return '';
    }
}
