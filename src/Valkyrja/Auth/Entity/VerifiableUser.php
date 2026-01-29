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

use Valkyrja\Auth\Entity\Contract\VerifiableUserContract;
use Valkyrja\Auth\Entity\Trait\MailableUserFields;
use Valkyrja\Auth\Entity\Trait\MailableUserMethods;
use Valkyrja\Auth\Entity\Trait\VerifiableUserFields;
use Valkyrja\Auth\Entity\Trait\VerifiableUserMethods;

class VerifiableUser extends User implements VerifiableUserContract
{
    use MailableUserFields;
    use MailableUserMethods;
    use VerifiableUserFields;
    use VerifiableUserMethods;
}
