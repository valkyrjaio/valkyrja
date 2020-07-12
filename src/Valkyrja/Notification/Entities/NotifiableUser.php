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

use Valkyrja\Auth\Entities\EmailableUserFields;
use Valkyrja\Auth\Entities\UserFields;
use Valkyrja\Notification\NotifiableUser as Contract;
use Valkyrja\ORM\Entities\EntityFields;

/**
 * Entity NotifiableUser.
 *
 * @author Melech Mizrachi
 */
class NotifiableUser implements Contract
{
    use EntityFields;
    use UserFields;
    use EmailableUserFields;
    use NotifiableUserFields;
}