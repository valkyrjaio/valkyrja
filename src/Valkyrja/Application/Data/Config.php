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

namespace Valkyrja\Application\Data;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\ApplicationComponentProvider;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;

class Config
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
     * @param class-string<ComponentProviderContract>[] $providers
     * @param array<callable(ApplicationContract):void> $callbacks
     */
    public function __construct(
        public readonly string $namespace = 'App',
        public readonly string $dir = __DIR__,
        public readonly string $version = ApplicationInfo::VERSION,
        public readonly string $environment = 'production',
        public readonly bool $debugMode = false,
        public readonly string $timezone = 'UTC',
        public readonly string $key = 'some_secret_app_key',
        public readonly string $dataPath = 'App/Provider/Data',
        public readonly string $dataNamespace = 'App\\Provider\\Data',
        public readonly array $providers = [
            ApplicationComponentProvider::class,
        ],
        public readonly array $callbacks = [],
    ) {
    }
}
