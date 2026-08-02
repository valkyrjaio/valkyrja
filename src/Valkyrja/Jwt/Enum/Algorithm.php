<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Jwt\Enum;

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
