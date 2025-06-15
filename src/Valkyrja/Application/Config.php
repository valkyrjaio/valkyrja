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

use Valkyrja\Application\Constant\ConfigName;
use Valkyrja\Application\Constant\EnvName;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Container\Provider\AppProvider as ContainerAppProvider;
use Valkyrja\Exception\Contract\ErrorHandler as ErrorHandlerContract;
use Valkyrja\Exception\ErrorHandler;

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
        ConfigName::ENV           => EnvName::ENV,
        ConfigName::DEBUG         => EnvName::DEBUG,
        ConfigName::URL           => EnvName::ENV,
        ConfigName::TIMEZONE      => EnvName::TIMEZONE,
        ConfigName::VERSION       => EnvName::VERSION,
        ConfigName::KEY           => EnvName::KEY,
        ConfigName::ERROR_HANDLER => EnvName::ERROR_HANDLER,
        ConfigName::PROVIDERS     => EnvName::PROVIDERS,
    ];

    /**
     * @param non-empty-string                   $timezone     The timezone
     * @param class-string<ErrorHandlerContract> $errorHandler The error handler
     * @param class-string<Provider>[]           $providers    The app providers
     */
    public function __construct(
        public string $env = 'production',
        public bool $debug = false,
        public string $url = 'localhost',
        public string $timezone = 'UTC',
        public string $version = Application::VERSION,
        public string $key = 'some_secret_app_key',
        public string $errorHandler = ErrorHandler::class,
        public array $providers = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        $this->providers[] = ContainerAppProvider::class;
    }
}
