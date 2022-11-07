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
use Valkyrja\Filesystem\Config\Config as Model;

use function Valkyrja\env;
use function Valkyrja\storagePath;

/**
 * Class Filesystem.
 */
class Filesystem extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->disks = [
            CKP::LOCAL => [
                CKP::ADAPTER           => env(EnvKey::FILESYSTEM_LOCAL_ADAPTER),
                CKP::DRIVER            => env(EnvKey::FILESYSTEM_LOCAL_DRIVER),
                CKP::FLYSYSTEM_ADAPTER => env(EnvKey::FILESYSTEM_LOCAL_FLYSYSTEM_ADAPTER, Local::class),
                CKP::DIR               => env(EnvKey::FILESYSTEM_LOCAL_DIR, storagePath('app')),
            ],
            CKP::S3    => [
                CKP::ADAPTER           => env(EnvKey::FILESYSTEM_S3_ADAPTER),
                CKP::DRIVER            => env(EnvKey::FILESYSTEM_S3_DRIVER),
                CKP::FLYSYSTEM_ADAPTER => env(EnvKey::FILESYSTEM_S3_FLYSYSTEM_ADAPTER, AwsS3Adapter::class),
                CKP::KEY               => env(EnvKey::FILESYSTEM_S3_KEY),
                CKP::SECRET            => env(EnvKey::FILESYSTEM_S3_SECRET),
                CKP::REGION            => env(EnvKey::FILESYSTEM_S3_REGION, 'us1'),
                CKP::VERSION           => env(EnvKey::FILESYSTEM_S3_VERSION, 'latest'),
                CKP::BUCKET            => env(EnvKey::FILESYSTEM_S3_BUCKET),
                CKP::PREFIX            => env(EnvKey::FILESYSTEM_S3_PREFIX, ''),
                CKP::OPTIONS           => env(EnvKey::FILESYSTEM_S3_OPTIONS, []),
            ],
        ];
    }
}
