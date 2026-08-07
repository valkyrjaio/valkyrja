<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Orm\Entity;

use Override;
use Valkyrja\Orm\Entity\Abstract\SoftDeleteEntity;

/**
 * Entity to use to test the soft delete.
 */
final class SoftDeleteEntityFixture extends SoftDeleteEntity
{
    public int $id;
    public string $name;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'test';
    }

    public function setId(string|int $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function internalSetCallables(): array
    {
        /* @phpstan-ignore return.type (The test gives invalid input on purpose to reach the guard.) */
        return [
            'id' => [$this, 'setId'],
        ];
    }
}
