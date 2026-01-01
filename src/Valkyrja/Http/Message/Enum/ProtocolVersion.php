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

namespace Valkyrja\Http\Message\Enum;

enum ProtocolVersion: string
{
    case V1   = '1.0';
    case V1_1 = '1.1';
    case V2   = '2';
    case V3   = '3';
}
