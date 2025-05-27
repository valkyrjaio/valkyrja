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

namespace Valkyrja\Mail;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Mail\Config\Configurations;
use Valkyrja\Mail\Config\DefaultMessageConfiguration;
use Valkyrja\Mail\Config\LogConfiguration;
use Valkyrja\Mail\Config\MailgunConfiguration;
use Valkyrja\Mail\Config\MessageConfigurations;
use Valkyrja\Mail\Config\NullConfiguration;
use Valkyrja\Mail\Config\PhpMailerConfiguration;
use Valkyrja\Mail\Constant\ConfigName;
use Valkyrja\Mail\Constant\EnvName;

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
        ConfigName::DEFAULT_MESSAGE_CONFIGURATION => EnvName::DEFAULT_MESSAGE_CONFIGURATION,
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
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->configurations === null) {
            $this->configurations = new Configurations(
                mailgun: MailgunConfiguration::fromEnv($env),
                phpMailer: PhpMailerConfiguration::fromEnv($env),
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
            $this->defaultConfiguration = array_key_first((array) $this->configurations);
        }

        if ($this->defaultMessageConfiguration === '') {
            $this->defaultMessageConfiguration = array_key_first((array) $this->messageConfigurations);
        }
    }
}
