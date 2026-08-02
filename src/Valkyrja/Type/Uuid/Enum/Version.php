<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uuid\Enum;

enum Version: int
{
    case V1 = 1;
    case V3 = 3;
    case V4 = 4;
    case V5 = 5;
    case V6 = 6;
    case V7 = 7;
    case V8 = 8;
}
