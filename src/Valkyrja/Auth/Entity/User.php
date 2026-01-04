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

use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\Trait\UserFields;
use Valkyrja\Auth\Entity\Trait\UserTrait;
use Valkyrja\Orm\Entity\Abstract\Entity;

class User extends Entity implements UserContract
{
    use UserFields;
    use UserTrait;

    /**
     * @inheritDoc
     *
     * @var non-empty-string
     */
    protected static string $tableName = 'users';
}
