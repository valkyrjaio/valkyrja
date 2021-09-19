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

use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Filesystem\Adapters\FlysystemAdapter;
use Valkyrja\Filesystem\Drivers\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT = CKP::LOCAL;
    public const ADAPTER = FlysystemAdapter::class;
    public const DRIVER  = Driver::class;
    public const DISKS   = [
        CKP::LOCAL => [
            CKP::ADAPTER           => CKP::FLYSYSTEM,
            CKP::DRIVER            => CKP::DEFAULT,
            CKP::FLYSYSTEM_ADAPTER => Local::class,
            CKP::DIR               => '/',
        ],
        CKP::S3    => [
            CKP::ADAPTER           => CKP::FLYSYSTEM,
            CKP::DRIVER            => CKP::DEFAULT,
            CKP::FLYSYSTEM_ADAPTER => AwsS3Adapter::class,
            CKP::KEY               => '',
            CKP::SECRET            => '',
            CKP::REGION            => 'us1',
            CKP::VERSION           => 'latest',
            CKP::BUCKET            => '',
            CKP::PREFIX            => '',
            CKP::OPTIONS           => [],
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTER,
        CKP::DRIVERS  => self::DRIVER,
        CKP::DISKS    => self::DISKS,
    ];
}
