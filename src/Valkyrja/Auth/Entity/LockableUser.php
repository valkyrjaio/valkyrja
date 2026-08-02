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

use Valkyrja\Auth\Entity\Contract\LockableUserContract;
use Valkyrja\Auth\Entity\Trait\LockableUserFields;
use Valkyrja\Auth\Entity\Trait\LockableUserMethods;

class LockableUser extends User implements LockableUserContract
{
    use LockableUserFields;
    use LockableUserMethods;
}
