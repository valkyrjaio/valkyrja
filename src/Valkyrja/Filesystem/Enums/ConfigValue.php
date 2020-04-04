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
use Valkyrja\Filesystem\FlysystemLocal;
use Valkyrja\Filesystem\FlysystemS3;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::LOCAL;
    public const ADAPTERS = [
        CKP::LOCAL => FlysystemLocal::class,
        CKP::S3    => FlysystemS3::class,
    ];
    public const DISKS    = [
        CKP::LOCAL => [],
        CKP::S3    => [],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::DISKS    => self::DISKS,
    ];
}
