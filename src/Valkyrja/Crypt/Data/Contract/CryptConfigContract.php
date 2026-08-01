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

namespace Valkyrja\Crypt\Data\Contract;

use Valkyrja\Crypt\Manager\Contract\CryptContract;

interface CryptConfigContract
{
    /** @var class-string<CryptContract> */
    public string $defaultCrypt {
        get;
    }
}
