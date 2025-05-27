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

use League\Flysystem\AwsS3V3\AwsS3V3Adapter as FlysystemAwsS3Adapter;
use Valkyrja\Filesystem\Constant\ConfigName;

/**
 * Class S3FlysystemConfiguration.
 *
 * @author Melech Mizrachi
 */
class S3FlysystemConfiguration extends FlysystemConfiguration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS     => 'FILESYSTEM_FLYSYSTEM_S3_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS      => 'FILESYSTEM_FLYSYSTEM_S3_DRIVER_CLASS',
        ConfigName::FLYSYSTEM_ADAPTER => 'FILESYSTEM_FLYSYSTEM_S3_FLYSYSTEM_ADAPTER',
        ConfigName::KEY               => 'FILESYSTEM_FLYSYSTEM_S3_KEY',
        ConfigName::SECRET            => 'FILESYSTEM_FLYSYSTEM_S3_SECRET',
        ConfigName::REGION            => 'FILESYSTEM_FLYSYSTEM_S3_REGION',
        ConfigName::VERSION           => 'FILESYSTEM_FLYSYSTEM_S3_VERSION',
        ConfigName::BUCKET            => 'FILESYSTEM_FLYSYSTEM_S3_BUCKET',
        ConfigName::PREFIX            => 'FILESYSTEM_FLYSYSTEM_S3_PREFIX',
        ConfigName::OPTIONS           => 'FILESYSTEM_FLYSYSTEM_S3_OPTIONS',
    ];

    public function __construct(
        public string $key = '',
        public string $secret = '',
        public string $region = 'us1',
        public string $version = 'latest',
        public string $bucket = '',
        public string $prefix = '',
        public array $options = [],
        public string $flysystemAdapter = FlysystemAwsS3Adapter::class,
    ) {
        parent::__construct(
            flysystemAdapter: FlysystemAwsS3Adapter::class,
        );
    }
}
