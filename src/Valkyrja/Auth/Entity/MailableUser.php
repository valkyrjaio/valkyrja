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

use Valkyrja\Auth\Entity\Contract\MailableUserContract;
use Valkyrja\Auth\Entity\Trait\MailableUserFields;
use Valkyrja\Auth\Entity\Trait\MailableUserMethods;

class MailableUser extends User implements MailableUserContract
{
    use MailableUserFields;
    use MailableUserMethods;
}
