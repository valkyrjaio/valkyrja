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
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\View\Provider\ViewComponentProvider;

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
            ContainerComponentProvider::class,
            DispatchComponentProvider::class,
            CliInteractionComponentProvider::class,
            CliMiddlewareComponentProvider::class,
            CliRoutingComponentProvider::class,
            CliServerComponentProvider::class,
            EventComponentProvider::class,
            HttpMessageComponentProvider::class,
            HttpMiddlewareComponentProvider::class,
            HttpRoutingComponentProvider::class,
            HttpRoutingCliComponentProvider::class,
            HttpServerComponentProvider::class,
            LogComponentProvider::class,
            ViewComponentProvider::class,
        ],
        public readonly array $callbacks = [],
    ) {
    }
}
