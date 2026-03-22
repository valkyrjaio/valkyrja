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

namespace Valkyrja\Tests\Classes\Cli\Interaction\Data;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ProviderContract;
use Valkyrja\Cli\Interaction\Data\Contract\ConfigContract;

final class ConfigClass extends Config implements ConfigContract
{
    /**
     * @param non-empty-string                          $namespace
     * @param non-empty-string                          $dir
     * @param non-empty-string                          $version
     * @param non-empty-string                          $environment
     * @param non-empty-string                          $timezone
     * @param non-empty-string                          $key
     * @param non-empty-string                          $dataPath
     * @param non-empty-string                          $dataNamespace
     * @param class-string<ProviderContract>[]          $providers
     * @param array<callable(ApplicationContract):void> $callbacks
     */
    public function __construct(
        string $namespace = 'App',
        string $dir = __DIR__,
        string $version = ApplicationInfo::VERSION,
        string $environment = 'production',
        bool $debugMode = false,
        string $timezone = 'UTC',
        string $key = 'some_secret_app_key',
        string $dataPath = 'App/Provider/Data',
        string $dataNamespace = 'App\\Provider\\Data',
        array $providers = [
            ComponentClass::CONTAINER,
            ComponentClass::DISPATCHER,
            ComponentClass::CLI_INTERACTION,
            ComponentClass::CLI_MIDDLEWARE,
            ComponentClass::CLI_ROUTING,
            ComponentClass::CLI_SERVER,
            ComponentClass::EVENT,
            ComponentClass::HTTP_MESSAGE,
            ComponentClass::HTTP_MIDDLEWARE,
            ComponentClass::HTTP_ROUTING,
            ComponentClass::HTTP_ROUTING_CLI,
            ComponentClass::HTTP_SERVER,
            ComponentClass::LOG,
        ],
        array $callbacks = [],
        public bool $isQuiet = false,
        public bool $isInteractive = true,
        public bool $isSilent = false,
    ) {
        parent::__construct(
            namespace: $namespace,
            dir: $dir,
            version: $version,
            environment: $environment,
            debugMode: $debugMode,
            timezone: $timezone,
            key: $key,
            dataPath: $dataPath,
            dataNamespace: $dataNamespace,
            providers: $providers,
            callbacks: $callbacks,
        );
    }
}
