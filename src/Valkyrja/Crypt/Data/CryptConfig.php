<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
