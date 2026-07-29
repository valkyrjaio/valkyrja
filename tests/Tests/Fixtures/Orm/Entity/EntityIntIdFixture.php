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
use Valkyrja\Orm\Entity\Abstract\Entity;

use function is_scalar;

/**
 * Model class to use to test abstract model.
 */
final class EntityIntIdFixture extends Entity
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

    public function setId(mixed $id): void
    {
        $this->id = (int) (is_scalar($id) ? $id : 0);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function internalSetCallables(): array
    {
        return [
            'id' => [$this, 'setId'],
        ];
    }
}
