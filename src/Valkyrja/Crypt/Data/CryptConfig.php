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

namespace Valkyrja\Crypt\Data;

use Valkyrja\Crypt\Data\Contract\CryptConfigContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\SodiumCrypt;

class CryptConfig implements CryptConfigContract
{
    /**
     * @param class-string<CryptContract> $defaultCrypt The crypt to use by default
     */
    public function __construct(
        public readonly string $defaultCrypt = SodiumCrypt::class,
    ) {
    }
}
