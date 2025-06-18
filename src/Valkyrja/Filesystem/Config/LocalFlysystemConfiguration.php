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

use League\Flysystem\Local\LocalFilesystemAdapter as FlysystemLocalAdapter;
use Valkyrja\Filesystem\Constant\ConfigName;
use Valkyrja\Filesystem\Constant\EnvName;
use Valkyrja\Support\Directory;

/**
 * Class LocalFlysystemConfiguration.
 *
 * @author Melech Mizrachi
 */
class LocalFlysystemConfiguration extends FlysystemConfiguration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS     => EnvName::FLYSYSTEM_LOCAL_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS      => EnvName::FLYSYSTEM_LOCAL_DRIVER_CLASS,
        ConfigName::FLYSYSTEM_ADAPTER => EnvName::FLYSYSTEM_LOCAL_FLYSYSTEM_ADAPTER,
        ConfigName::DIR               => EnvName::FLYSYSTEM_LOCAL_DIR,
    ];

    public function __construct(
        public string $dir = '',
    ) {
        parent::__construct(
            flysystemAdapter: FlysystemLocalAdapter::class,
        );

        if ($this->dir === '') {
            $this->dir = Directory::storagePath('app');
        }
    }
}
