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

use Valkyrja\Sms\Config\Configurations;
use Valkyrja\Sms\Config\DefaultMessageConfiguration;
use Valkyrja\Sms\Config\LogConfiguration;
use Valkyrja\Sms\Config\MessageConfigurations;
use Valkyrja\Sms\Config\NullConfiguration;
use Valkyrja\Sms\Config\VonageConfiguration;
use Valkyrja\Sms\Constant\ConfigName;
use Valkyrja\Sms\Constant\EnvName;
use Valkyrja\Support\Config as ParentConfig;

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
        ConfigName::DEFAULT_CONFIGURATION         => EnvName::DEFAULT_CONFIGURATION,
        ConfigName::CONFIGURATIONS                => EnvName::CONFIGURATIONS,
        ConfigName::DEFAULT_MESSAGE_CONFIGURATION => EnvName::DEFAULT_MESSAGE_CONFIGURATION,
        ConfigName::MESSAGE_CONFIGURATIONS        => EnvName::MESSAGE_CONFIGURATIONS,
    ];

    public function __construct(
        public string $defaultConfiguration = '',
        public Configurations|null $configurations = null,
        public string $defaultMessageConfiguration = '',
        public MessageConfigurations|null $messageConfigurations = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setPropertiesFromEnv(string $env): void
    {
        if ($this->configurations === null) {
            $this->configurations = new Configurations(
                vonage: VonageConfiguration::fromEnv($env),
                log: LogConfiguration::fromEnv($env),
                null: NullConfiguration::fromEnv($env)
            );
        }

        if ($this->messageConfigurations === null) {
            $this->messageConfigurations = new MessageConfigurations(
                default: DefaultMessageConfiguration::fromEnv($env)
            );
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = (string) array_key_first((array) $this->configurations);
        }

        if ($this->defaultMessageConfiguration === '') {
            $this->defaultMessageConfiguration = (string) array_key_first((array) $this->messageConfigurations);
        }

        parent::setPropertiesFromEnv($env);
    }
}
