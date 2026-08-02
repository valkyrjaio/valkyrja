<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Stream\Enum;

/**
 * @see https://www.php.net/manual/en/function.fopen.php
 */
enum ModeTranslation: string
{
    case NONE        = '';
    case WINDOWS     = 't';
    case BINARY_SAFE = 'b';
}
