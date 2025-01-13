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

namespace Valkyrja\Http\Message\File\Enum;

/**
 * Enum UploadError.
 *
 * @author Melech Mizrachi
 */
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
