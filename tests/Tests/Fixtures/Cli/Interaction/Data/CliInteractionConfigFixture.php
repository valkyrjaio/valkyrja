<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Data;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;

final class CliInteractionConfigFixture extends Config implements CliInteractionConfigContract
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
     * @param ComponentProviderContract[]               $providers
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
            new CliWithHttpApplicationComponentProvider(),
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
