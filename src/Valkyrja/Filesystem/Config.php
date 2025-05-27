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

namespace Valkyrja\Filesystem;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Filesystem\Config\Configurations;
use Valkyrja\Filesystem\Config\InMemoryConfiguration;
use Valkyrja\Filesystem\Config\LocalFlysystemConfiguration;
use Valkyrja\Filesystem\Config\NullConfiguration;
use Valkyrja\Filesystem\Config\S3FlysystemConfiguration;
use Valkyrja\Filesystem\Constant\ConfigName;
use Valkyrja\Filesystem\Constant\EnvName;

use function array_key_first;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::DEFAULT_CONFIGURATION => EnvName::DEFAULT_CONFIGURATION,
    ];

    public function __construct(
        public string $defaultConfiguration = '',
        public Configurations|null $configurations = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->configurations === null) {
            $this->configurations = new Configurations(
                local: LocalFlysystemConfiguration::fromEnv($env),
                memory: InMemoryConfiguration::fromEnv($env),
                s3: S3FlysystemConfiguration::fromEnv($env),
                null: NullConfiguration::fromEnv($env),
            );
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = array_key_first((array) $this->configurations);
        }
    }
}
