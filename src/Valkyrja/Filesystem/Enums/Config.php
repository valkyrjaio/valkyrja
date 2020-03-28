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

namespace Valkyrja\Filesystem\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\Filesystem\FlysystemLocal;
use Valkyrja\Filesystem\FlysystemS3;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const ADAPTERS = [
        CKP::LOCAL => FlysystemLocal::class,
        CKP::S3    => FlysystemS3::class,
    ];
}
