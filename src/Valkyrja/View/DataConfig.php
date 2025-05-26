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

namespace Valkyrja\View;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Support\Directory;
use Valkyrja\View\Config\Configuration;
use Valkyrja\View\Config\OrkaConfiguration;
use Valkyrja\View\Config\PhpConfiguration;
use Valkyrja\View\Config\TwigConfiguration;
use Valkyrja\View\Constant\ConfigName;
use Valkyrja\View\Constant\EnvName;

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
        ConfigName::DIR                   => EnvName::DIR,
        ConfigName::PATH                  => EnvName::PATH,
        ConfigName::DEFAULT_CONFIGURATION => EnvName::DEFAULT_CONFIGURATION,
        ConfigName::CONFIGURATIONS        => EnvName::CONFIGURATIONS,
    ];

    /**
     * @param string                       $dir
     * @param string                       $path
     * @param string                       $defaultConfiguration
     * @param array<string, Configuration> $configurations
     */
    public function __construct(
        public string $dir = '',
        public string $path = '',
        public string $defaultConfiguration = '',
        public array $configurations = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->dir === '') {
            $this->dir = Directory::resourcesPath('views');
        }

        if ($this->configurations === []) {
            $this->configurations = [
                'php'  => PhpConfiguration::fromEnv($env),
                'orka' => OrkaConfiguration::fromEnv($env),
                'twig' => TwigConfiguration::fromEnv($env),
            ];
        }

        if ($this->defaultConfiguration === '') {
            $this->defaultConfiguration = array_key_first($this->configurations);
        }
    }
}
