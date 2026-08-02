<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\File\Enum;

enum UploadError: int
{
    case OK         = 0;
    case INI_SIZE   = 1;
    case FORM_SIZE  = 2;
    case PARTIAL    = 3;
    case NO_FILE    = 4;
    case NO_TMP_DIR = 6;
    case CANT_WRITE = 7;
    case EXTENSION  = 8;
}
