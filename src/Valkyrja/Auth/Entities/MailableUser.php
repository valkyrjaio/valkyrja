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

namespace Valkyrja\Auth\Entities;

use Valkyrja\Auth\MailableUser as Contract;
use Valkyrja\ORM\Entities\EntityFields;

/**
 * Entity MailableUser.
 *
 * @author Melech Mizrachi
 */
class MailableUser implements Contract
{
    use EntityFields;
    use UserFields;
    use MailableUserFields;
}