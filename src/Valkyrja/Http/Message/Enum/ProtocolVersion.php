<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Enum;

enum ProtocolVersion: string
{
    case V1   = '1.0';
    case V1_1 = '1.1';
    case V2   = '2';
    case V3   = '3';
}
