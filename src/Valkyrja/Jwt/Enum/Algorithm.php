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

namespace Valkyrja\Jwt\Enum;

/**
 * Enum Algorithm.
 */
enum Algorithm
{
    case HS256;
    case HS384;
    case HS512;

    case PS256;
    case PS384;
    case PS512;

    case RS256;
    case RS384;
    case RS512;

    case ES256;
    case ES256K;
    case ES384;
    case ES512;

    case EdDSA;
}
