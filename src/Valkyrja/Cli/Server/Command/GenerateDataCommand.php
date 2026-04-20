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

namespace Valkyrja\Cli\Server\Command;

use Override;
use Valkyrja\Application\Cli\Command\Abstract\GenerateData;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Provider\CliRoutingCliRouteProvider;
use Valkyrja\Cli\Server\Constant\CommandName;

class GenerateDataCommand extends GenerateData
{
    public function __construct(
        Env $env,
        protected CliConfigContract $config,
        OutputFactoryContract $outputFactory,
    ) {
        parent::__construct(
            env: $env,
            outputFactory: $outputFactory,
            title: 'Generating Cli Component Data',
        );
    }

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to generate all data classes for the Cli component.');
    }

    #[Route(
        name: CommandName::DATA_GENERATE,
        description: 'Generate data for the cli component',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRoutingCliRouteProvider::class, 'generateDataHandler'])]
    public function run(): OutputContract
    {
        return $this->generateData();
    }

    /**
     * Get the debug config.
     */
    #[Override]
    protected function getDebugConfig(): CliConfigContract
    {
        // Psalm is silly and not figuring out the type from the property hooks defined in the contract; this is a workaround
        /** @psalm-var CliConfig $config */
        $config = $this->config;

        return new CliConfig(
            namespace: $config->namespace,
            dir: $config->dir,
            version: $config->version,
            environment: $config->environment,
            debugMode: true,
            timezone: $config->timezone,
            key: $config->key,
            dataPath: $config->dataPath,
            dataNamespace: $config->dataNamespace,
            applicationName: $config->applicationName,
            defaultCommandName: $config->defaultCommandName,
            providers: $config->providers,
            callbacks: $config->callbacks,
            http: $config->http,
        );
    }
}
