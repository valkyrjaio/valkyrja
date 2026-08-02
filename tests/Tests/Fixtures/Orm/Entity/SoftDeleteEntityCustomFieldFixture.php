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
use Valkyrja\Orm\Entity\Contract\SoftDeleteEntityContract;

/**
 * Entity to use to test the soft delete with a field that is not the default.
 *
 * The field keeps the name that the framework used before the convention
 * change, which shows that an application with an older schema still works.
 */
final class SoftDeleteEntityCustomFieldFixture extends Entity implements SoftDeleteEntityContract
{
    public int $id;
    public string $name;
    public string|null $date_deleted = null;

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
        return [
            'id' => [$this, 'setId'],
        ];
    }
}
