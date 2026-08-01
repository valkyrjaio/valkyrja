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

namespace Valkyrja\Jwt\Data\Contract;

use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

interface JwtConfigContract
{
    /** @var class-string<JwtContract> */
    public string $defaultJwt {
        get;
    }

    public Algorithm $algorithm {
        get;
    }
}
