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

namespace Valkyrja\Http\Routing\Cli\Command;

use Override;
use Valkyrja\Application\Cli\Command\Abstract\GenerateData;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Cli\Command\Constant\CommandName;

class GenerateDataCommand extends GenerateData
{
    public function __construct(
        protected Env $env,
        protected HttpConfig $config,
        protected OutputFactoryContract $outputFactory,
    ) {
        parent::__construct(
            env: $env,
            outputFactory: $outputFactory,
            title: 'Generating Http Component Data',
        );
    }

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to generate all data classes for the Http component.');
    }

    #[Route(
        name: CommandName::DATA_GENERATE,
        description: 'Generate data for the http component',
        helpText: [self::class, 'help'],
    )]
    public function run(): OutputContract
    {
        return $this->generateData();
    }

    /**
     * Get the debug config.
     */
    #[Override]
    protected function getDebugConfig(): HttpConfig
    {
        $config = $this->config;

        return new HttpConfig(
            namespace: $config->namespace,
            dir: $config->dir,
            version: $config->version,
            environment: $config->environment,
            debugMode: true,
            timezone: $config->timezone,
            key: $config->key,
            dataPath: $config->dataPath,
            dataNamespace: $config->dataNamespace,
            providers: $config->providers,
            callbacks: $config->callbacks,
        );
    }
}
