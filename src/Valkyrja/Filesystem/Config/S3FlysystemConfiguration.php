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

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use Valkyrja\Filesystem\Constant\ConfigName;
use Valkyrja\Filesystem\Constant\EnvName;

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
        ConfigName::ADAPTER_CLASS     => EnvName::FLYSYSTEM_S3_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS      => EnvName::FLYSYSTEM_S3_DRIVER_CLASS,
        ConfigName::FLYSYSTEM_ADAPTER => EnvName::FLYSYSTEM_S3_FLYSYSTEM_ADAPTER,
        ConfigName::KEY               => EnvName::FLYSYSTEM_S3_KEY,
        ConfigName::SECRET            => EnvName::FLYSYSTEM_S3_SECRET,
        ConfigName::REGION            => EnvName::FLYSYSTEM_S3_REGION,
        ConfigName::VERSION           => EnvName::FLYSYSTEM_S3_VERSION,
        ConfigName::BUCKET            => EnvName::FLYSYSTEM_S3_BUCKET,
        ConfigName::PREFIX            => EnvName::FLYSYSTEM_S3_PREFIX,
        ConfigName::OPTIONS           => EnvName::FLYSYSTEM_S3_OPTIONS,
    ];

    /**
     * @param array<string, mixed> $options [optional] Options passed directly to the Flysystem S3 adapter
     */
    public function __construct(
        public string $key = '',
        public string $secret = '',
        public string $region = 'us1',
        public string $version = 'latest',
        public string $bucket = '',
        public string $prefix = '',
        public array $options = [],
    ) {
        parent::__construct(
            flysystemAdapter: AwsS3V3Adapter::class,
        );
    }
}
