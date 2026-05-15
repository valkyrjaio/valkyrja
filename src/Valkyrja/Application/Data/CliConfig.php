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
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Server\Constant\CommandName;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;

class CliConfig implements CliConfigContract
{
    /**
     * @param non-empty-string                                  $namespace
     * @param non-empty-string                                  $dir
     * @param non-empty-string                                  $version
     * @param non-empty-string                                  $environment
     * @param non-empty-string                                  $timezone
     * @param non-empty-string                                  $key
     * @param non-empty-string                                  $dataPath
     * @param non-empty-string                                  $dataNamespace
     * @param non-empty-string                                  $applicationName
     * @param non-empty-string                                  $defaultCommandName
     * @param ComponentProviderContract[]                        $providers
     * @param array<callable(ApplicationContract):void>         $callbacks
     * @param class-string<InputReceivedMiddlewareContract>[]   $inputReceivedMiddleware
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware
     * @param class-string<RouteNotMatchedMiddlewareContract>[] $routeNotMatchedMiddleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware
     * @param class-string<ExitedMiddlewareContract>[]          $exitedMiddleware
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
        public readonly string $applicationName = 'valkyrja',
        public readonly string $defaultCommandName = CommandName::LIST,
        public readonly array $providers = [
            new CliWithHttpApplicationComponentProvider(),
        ],
        public readonly array $callbacks = [],
        public readonly array $inputReceivedMiddleware = [
            CheckForHelpOptionsMiddleware::class,
            CheckForVersionOptionsMiddleware::class,
            CheckGlobalInteractionOptionsMiddleware::class,
        ],
        public readonly array $routeMatchedMiddleware = [],
        public readonly array $routeNotMatchedMiddleware = [
            CheckCommandForTypoMiddleware::class,
        ],
        public readonly array $routeDispatchedMiddleware = [],
        public readonly array $throwableCaughtMiddleware = [
            LogThrowableCaughtMiddleware::class,
            OutputThrowableCaughtMiddleware::class,
        ],
        public readonly array $exitedMiddleware = [],
    ) {
    }
}
