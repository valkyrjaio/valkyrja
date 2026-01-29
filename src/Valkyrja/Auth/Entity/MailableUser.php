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

namespace Valkyrja\Auth\Entity;

use Valkyrja\Auth\Entity\Contract\MailableUserContract;
use Valkyrja\Auth\Entity\Trait\MailableUserFields;
use Valkyrja\Auth\Entity\Trait\MailableUserMethods;

class MailableUser extends User implements MailableUserContract
{
    use MailableUserFields;
    use MailableUserMethods;
}
