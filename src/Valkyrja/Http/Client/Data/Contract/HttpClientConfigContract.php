<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Client\Data\Contract;

use Valkyrja\Http\Client\Manager\Contract\ClientContract;

interface HttpClientConfigContract
{
    /** @var class-string<ClientContract> */
    public string $defaultClient {
        get;
    }
}
