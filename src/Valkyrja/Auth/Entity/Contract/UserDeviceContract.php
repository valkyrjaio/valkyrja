<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Contract;

use Valkyrja\Orm\Entity\Contract\EntityContract;

interface UserDeviceContract extends EntityContract
{
    /**
     * Get the user id field.
     */
    public static function getUserIdField(): string;
}
