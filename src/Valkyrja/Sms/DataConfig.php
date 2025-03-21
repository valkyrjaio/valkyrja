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

namespace Valkyrja\Sms;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Sms\Config\Configuration;
use Valkyrja\Sms\Config\DefaultMessageConfiguration;
use Valkyrja\Sms\Config\LogConfiguration;
use Valkyrja\Sms\Config\MessageConfiguration;
use Valkyrja\Sms\Config\NullConfiguration;
use Valkyrja\Sms\Config\VonageConfiguration;
use Valkyrja\Sms\Constant\ConfigName;
use Valkyrja\Sms\Constant\EnvName;

use function array_key_first;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class DataConfig extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::DEFAULT_CONFIGURATION         => EnvName::DEFAULT_CONFIGURATION,
        ConfigName::CONFIGURATIONS                => EnvName::CONFIGURATIONS,
        ConfigName::DEFAULT_MESSAGE_CONFIGURATION => EnvName::DEFAULT_MESSAGE_CONFIGURATION,
        ConfigName::MESSAGE_CONFIGURATIONS        => EnvName::MESSAGE_CONFIGURATIONS,
    ];

    /**
     * @param string                              $defaultConfiguration
     * @param array<string, Configuration>        $configurations
     * @param string                              $defaultMessageConfiguration
     * @param array<string, MessageConfiguration> $messageConfiguration
     */
    public function __construct(
        public string $defaultConfiguration = '',
        public array $configurations = [],
        public string $defaultMessageConfiguration = '',
        public array $messageConfiguration = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->configurations === []) {
            $this->configurations = [
                'vonage' => VonageConfiguration::fromEnv($env),
                'log'    => LogConfiguration::fromEnv($env),
                'null'   => NullConfiguration::fromEnv($env),
            ];
        }

        if ($this->messageConfiguration === []) {
            $this->messageConfiguration = [
                'default' => DefaultMessageConfiguration::fromEnv($env),
            ];
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = array_key_first($this->configurations);
        }

        if ($this->defaultMessageConfiguration === '') {
            $this->defaultMessageConfiguration = array_key_first($this->messageConfiguration);
        }
    }
}
