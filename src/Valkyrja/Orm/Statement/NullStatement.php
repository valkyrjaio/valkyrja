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

use stdClass;
use Valkyrja\Orm\Statement as Contract;

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
    public function bindValue(string $parameter, mixed $value): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getColumnMeta(int $columnNumber): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function fetchColumn(int $columnNumber = 0): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function fetchObject(string $className = stdClass::class): object
    {
        return new $className();
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function errorCode(): string
    {
        return '00000';
    }

    /**
     * @inheritDoc
     */
    public function errorMessage(): string|null
    {
        return null;
    }
}
