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

namespace Valkyrja\Log;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Log\Config\Configurations;
use Valkyrja\Log\Config\PsrConfiguration;
use Valkyrja\Log\Constant\ConfigName;
use Valkyrja\Log\Constant\EnvName;

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
                psr: PsrConfiguration::fromEnv($env),
            );
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = array_key_first((array) $this->configurations);
        }
    }
}
