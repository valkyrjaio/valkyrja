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

namespace Valkyrja\Notification\Entity;

use Valkyrja\Auth\Entity\Trait\MailableUserFields;
use Valkyrja\Auth\Entity\Trait\MailableUserTrait;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Notification\Entity\Contract\NotifiableUserContract;
use Valkyrja\Notification\Entity\Trait\NotifiableUserFields;
use Valkyrja\Notification\Entity\Trait\NotifiableUserTrait;

class NotifiableUser extends User implements NotifiableUserContract
{
    use MailableUserFields;
    use MailableUserTrait;
    use NotifiableUserFields;
    use NotifiableUserTrait;
}
