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
use Valkyrja\Sms\Config\Configurations;
use Valkyrja\Sms\Config\DefaultMessageConfiguration;
use Valkyrja\Sms\Config\LogConfiguration;
use Valkyrja\Sms\Config\MessageConfigurations;
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
        ConfigName::DEFAULT_MESSAGE_CONFIGURATION => EnvName::DEFAULT_MESSAGE_CONFIGURATION,
    ];

    public function __construct(
        public string $defaultConfiguration = '',
        public Configurations|null $configurations = null,
        public string $defaultMessageConfiguration = '',
        public MessageConfigurations|null $messageConfiguration = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->configurations === null) {
            $this->configurations = new Configurations(
                vonage: VonageConfiguration::fromEnv($env),
                log: LogConfiguration::fromEnv($env),
                null: NullConfiguration::fromEnv($env)
            );
        }

        if ($this->messageConfiguration === null) {
            $this->messageConfiguration = new MessageConfigurations(
                default: DefaultMessageConfiguration::fromEnv($env)
            );
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = array_key_first((array) $this->configurations);
        }

        if ($this->defaultMessageConfiguration === '') {
            $this->defaultMessageConfiguration = array_key_first((array) $this->messageConfiguration);
        }
    }
}
