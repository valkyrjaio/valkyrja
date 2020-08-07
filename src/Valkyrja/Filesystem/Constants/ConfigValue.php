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

namespace Valkyrja\Filesystem\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Filesystem\Adapters\LocalFlysystemAdapter;
use Valkyrja\Filesystem\Adapters\S3FlysystemAdapter;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::LOCAL;
    public const ADAPTERS = [
        CKP::LOCAL => [
            CKP::DRIVER => LocalFlysystemAdapter::class,
            CKP::DIR    => '/',
        ],
        CKP::S3    => [
            CKP::DRIVER  => S3FlysystemAdapter::class,
            CKP::KEY     => '',
            CKP::SECRET  => '',
            CKP::REGION  => 'us1',
            CKP::VERSION => 'latest',
            CKP::BUCKET  => '',
            CKP::PREFIX  => '',
            CKP::OPTIONS => [],
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
    ];
}
