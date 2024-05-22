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

namespace Valkyrja\Notification\Entities;

use Valkyrja\Auth\Entity\MailableUserFields;
use Valkyrja\Auth\Entity\MailableUserTrait;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Notification\NotifiableUser as Contract;

/**
 * Entity NotifiableUser.
 *
 * @author Melech Mizrachi
 */
class NotifiableUser extends User implements Contract
{
    use MailableUserFields;
    use MailableUserTrait;
    use NotifiableUserFields;
    use NotifiableUserTrait;
}
