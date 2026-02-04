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

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Server\Constant\CommandName;

use function is_string;

class ListBashCommand
{
    public function __construct(
        protected RouteContract $route,
        protected CollectionContract $collection,
        protected OutputFactoryContract $outputFactory
    ) {
    }

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to list all the commands present within the Cli component for bash completion.');
    }

    #[Route(
        name: CommandName::LIST_BASH,
        description: 'List all commands for bash completion',
        helpText: [self::class, 'help'],
        arguments: [
            new ArgumentParameter(
                name: 'applicationName',
                description: 'The application name',
            ),
            new ArgumentParameter(
                name: 'namespace',
                description: 'An optional namespace to filter commands by',
            ),
        ]
    )]
    public function run(): OutputContract
    {
        $output = $this->outputFactory
            ->createOutput();

        $namespace = $this->route->getArgument('namespace')?->getFirstValue();
        $routes    = $this->collection->all();
        $colonAt   = false;

        if (is_string($namespace)) {
            $colonAt = strpos($namespace, ':');

            $routes = array_filter($routes, static fn (RouteContract $route) => str_starts_with($route->getName(), $namespace));
        }

        $routesForBash = array_map(
            static fn (RouteContract $route): string => $colonAt !== false ? substr($route->getName(), $colonAt + 1) : $route->getName(),
            $routes
        );

        /** @var non-empty-string $routesForBashString */
        $routesForBashString = implode(' ', $routesForBash);

        /** @psalm-suppress ArgumentTypeCoercion */
        return $output
            ->withAddedMessages(
                new Message($routesForBashString)
            );
    }
}
