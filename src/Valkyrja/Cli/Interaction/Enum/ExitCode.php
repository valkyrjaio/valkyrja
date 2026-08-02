<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Enum;

enum ExitCode: int
{
    case SUCCESS = 0;
    case ERROR   = 1;

    case USAGE_ERROR    = 64;
    case DATA_ERROR     = 65;
    case NO_INPUT       = 67;
    case NO_USER        = 68;
    case UNAVAILABLE    = 69;
    case SOFTWARE_ERROR = 70;
    case OS_ERROR       = 71;
    case OS_FILE_ERROR  = 72;
    case CANT_CREATE    = 73;
    case IO_ERROR       = 74;
    case TEMP_FAIL      = 75;
    case PROTOCOL_ERROR = 76;
    case NO_PERMISSION  = 77;
    case CONFIG_ERROR   = 78;

    case AUTO_EXIT = 255;
}
