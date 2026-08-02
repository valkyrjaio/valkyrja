<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Jwt\Data\Contract;

interface JwtRsConfigContract
{
    /** @var non-empty-string */
    public string $rsPrivateKey {
        get;
    }

    /** @var non-empty-string */
    public string $rsPublicKey {
        get;
    }
}
