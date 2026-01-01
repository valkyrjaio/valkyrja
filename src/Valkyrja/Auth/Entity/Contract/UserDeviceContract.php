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

namespace Valkyrja\Auth\Entity\Contract;

use Valkyrja\Orm\Entity\Contract\EntityContract;

interface UserDeviceContract extends EntityContract
{
    /**
     * Get the user id field.
     *
     * @return string
     */
    public static function getUserIdField(): string;
}
