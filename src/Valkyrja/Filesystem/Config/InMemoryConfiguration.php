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

use Valkyrja\Filesystem\Adapter\InMemoryAdapter;
use Valkyrja\Filesystem\Constant\ConfigName;
use Valkyrja\Filesystem\Constant\EnvName;
use Valkyrja\Support\Directory;

/**
 * Class NullConfiguration.
 *
 * @author Melech Mizrachi
 */
class InMemoryConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::IN_MEMORY_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::IN_MEMORY_DRIVER_CLASS,
        ConfigName::DIR           => EnvName::IN_MEMORY_DIR,
    ];

    public function __construct(
        public string $dir = '',
    ) {
        parent::__construct(
            adapterClass: InMemoryAdapter::class,
        );

        if ($this->dir === '') {
            $this->dir = Directory::storagePath('app');
        }
    }
}
