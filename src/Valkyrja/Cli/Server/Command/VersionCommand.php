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

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\CliRoutingCliRouteProvider;
use Valkyrja\Cli\Server\Constant\CommandName;

use const PHP_VERSION;

class VersionCommand
{
    public function __construct(
        protected OutputFactoryContract $outputFactory,
        protected CliConfigContract $config,
        protected RouteContract $route,
    ) {
    }

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to show the application version and info.');
    }

    #[Route(
        name: CommandName::VERSION,
        description: 'Get the application version',
        helpText: [self::class, 'help'],
    )]
    #[OptionParameter(
        name: 'short',
        description: 'Output the version number only',
        shortNames: ['s'],
    )]
    #[OptionParameter(
        name: 'plain',
        description: 'Output version info without the banner',
        shortNames: ['p'],
    )]
    #[RouteHandler([CliRoutingCliRouteProvider::class, 'versionHandler'])]
    public function run(): OutputContract
    {
        if ($this->route->hasOption('short')) {
            return $this->outputFactory
                ->createOutput()
                ->withMessages(new Message($this->config->version));
        }

        if ($this->route->hasOption('plain')) {
            return $this->outputFactory
                ->createOutput()
                ->withMessages(
                    new Message($this->config->namespace . ' v' . $this->config->version),
                    new NewLine(),
                    new Message('Built on Valkyrja v' . ApplicationInfo::VERSION . ' (date: ' . ApplicationInfo::VERSION_BUILD_DATE_TIME . ')'),
                    new NewLine(),
                    new Message('Running on PHP ' . PHP_VERSION),
                );
        }

        return $this->outputFactory
            ->createOutput()
            ->withMessages(
                new Header($this->config->namespace, $this->config->version, $this->route),
            );
    }
}
