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

use Valkyrja\Auth\Entity\Contract\TokenizableUser as Contract;

/**
 * Entity TokenizableUser.
 *
 * @author Melech Mizrachi
 */
class TokenizableUser extends User implements Contract
{
    use TokenizableUserFields;
    use TokenizableUserTrait;
}
