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

namespace Valkyrja\Application;

use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Constant\ConfigName;
use Valkyrja\Application\Constant\EnvName;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Component;
use Valkyrja\Support\Config as ParentConfig;
use Valkyrja\Support\Directory;

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
        ConfigName::ENV             => EnvName::ENV,
        ConfigName::DEBUG_MODE      => EnvName::DEBUG_MODE,
        ConfigName::URL             => EnvName::URL,
        ConfigName::TIMEZONE        => EnvName::TIMEZONE,
        ConfigName::VERSION         => EnvName::VERSION,
        ConfigName::KEY             => EnvName::KEY,
        ConfigName::COMPONENTS      => EnvName::COMPONENTS,
        ConfigName::CACHE_FILE_PATH => EnvName::CACHE_FILE_PATH,
    ];

    /**
     * @param non-empty-string          $env        The environment
     * @param non-empty-string          $url        The url
     * @param non-empty-string          $timezone   The timezone
     * @param non-empty-string          $version    The version
     * @param non-empty-string          $key        The secret key
     * @param class-string<Component>[] $components The components
     */
    public function __construct(
        public string $env = 'production',
        public bool $debugMode = false,
        public string $url = 'localhost',
        public string $timezone = 'UTC',
        public string $version = Application::VERSION,
        public string $key = 'some_secret_app_key',
        public array $components = [],
        public string $cacheFilePath = ''
    ) {
    }

    public function setPropertiesFromEnv(string $env): void
    {
        if ($this->cacheFilePath === '') {
            $this->cacheFilePath = Directory::cachePath('config.php');
        }

        $this->components = [
            ComponentClass::API,
            ComponentClass::ASSET,
            ComponentClass::AUTH,
            ComponentClass::BROADCAST,
            ComponentClass::CACHE,
            ComponentClass::CRYPT,
            ComponentClass::FILESYSTEM,
            ComponentClass::HTTP_CLIENT,
            ComponentClass::JWT,
            ComponentClass::LOG,
            ComponentClass::MAIL,
            ComponentClass::NOTIFICATION,
            ComponentClass::ORM,
            ComponentClass::SESSION,
            ComponentClass::SMS,
            ComponentClass::VIEW,
        ];

        parent::setPropertiesFromEnv($env);
    }
}
