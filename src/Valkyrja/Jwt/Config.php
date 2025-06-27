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

namespace Valkyrja\Jwt;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Jwt\Config\Configurations;
use Valkyrja\Jwt\Config\EdDsaConfiguration;
use Valkyrja\Jwt\Config\HsConfiguration;
use Valkyrja\Jwt\Config\RsConfiguration;
use Valkyrja\Jwt\Constant\ConfigName;
use Valkyrja\Jwt\Constant\EnvName;

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
        ConfigName::CONFIGURATIONS        => EnvName::CONFIGURATIONS,
    ];

    public function __construct(
        public string $defaultConfiguration = '',
        public Configurations|null $configurations = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setPropertiesFromEnv(string $env): void
    {
        if ($this->configurations === null) {
            $this->configurations = new Configurations(
                hs: HsConfiguration::fromEnv($env),
                rs: RsConfiguration::fromEnv($env),
                edDsa: EdDsaConfiguration::fromEnv($env),
            );
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = (string) array_key_first((array) $this->configurations);
        }

        parent::setPropertiesFromEnv($env);
    }
}
