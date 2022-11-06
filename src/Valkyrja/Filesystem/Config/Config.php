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

namespace Valkyrja\Filesystem\Config;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Filesystem\Adapters\FlysystemAdapter;
use Valkyrja\Filesystem\Drivers\Driver;
use Valkyrja\Support\Manager\Config\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT => EnvKey::FILESYSTEM_DEFAULT,
        CKP::ADAPTER => EnvKey::FILESYSTEM_ADAPTER,
        CKP::DRIVER  => EnvKey::FILESYSTEM_DRIVER,
        CKP::DISKS   => EnvKey::FILESYSTEM_DISKS,
    ];

    /**
     * @inheritDoc
     */
    public string $default = CKP::LOCAL;

    /**
     * @inheritDoc
     */
    public string $adapter = FlysystemAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * The disks.
     *
     * @var array
     */
    public array $disks = [
        CKP::LOCAL => [
            CKP::ADAPTER           => null,
            CKP::DRIVER            => null,
            CKP::FLYSYSTEM_ADAPTER => Local::class,
            CKP::DIR               => '/',
        ],
        CKP::S3    => [
            CKP::ADAPTER           => null,
            CKP::DRIVER            => null,
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
}
