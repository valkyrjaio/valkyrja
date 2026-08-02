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

/**
 * Model class to use to test abstract model.
 */
final class EntityStringIdFixture extends Entity
{
    public string $id;
    public string $name;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'test';
    }
}
