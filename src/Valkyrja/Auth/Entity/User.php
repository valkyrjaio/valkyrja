<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity;

use Override;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\Trait\UserFields;
use Valkyrja\Auth\Entity\Trait\UserMethods;
use Valkyrja\Orm\Entity\Abstract\Entity;

class User extends Entity implements UserContract
{
    use UserFields;
    use UserMethods;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'users';
    }
}
