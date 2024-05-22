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

namespace Valkyrja\Filesystem\Constant;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter as FlysystemAwsS3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter as FlysystemLocalAdapter;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Filesystem\Adapter\FlysystemAdapter;
use Valkyrja\Filesystem\Driver\Driver;

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
            CKP::FLYSYSTEM_ADAPTER => FlysystemLocalAdapter::class,
            CKP::DIR               => '/',
        ],
        CKP::S3    => [
            CKP::ADAPTER           => CKP::FLYSYSTEM,
            CKP::DRIVER            => CKP::DEFAULT,
            CKP::FLYSYSTEM_ADAPTER => FlysystemAwsS3Adapter::class,
            CKP::KEY               => '',
            CKP::SECRET            => '',
            CKP::REGION            => 'us1',
            CKP::VERSION           => 'latest',
            CKP::BUCKET            => '',
            CKP::PREFIX            => '',
            CKP::OPTIONS           => [],
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::DISKS   => self::DISKS,
    ];
}
