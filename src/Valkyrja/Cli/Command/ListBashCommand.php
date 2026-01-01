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

namespace Valkyrja\Cli\Command;

use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

use function is_string;

/**
 * Class ListBashCommand.
 *
 * @author Melech Mizrachi
 */
class ListBashCommand
{
    public const string NAME = 'list:bash';

    #[Route(
        name: self::NAME,
        description: 'List all commands for bash completion',
        helpText: new Message('A command to list all the commands present within the Cli component for bash completion.'),
        parameters: [
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
    public function run(RouteContract $route, CollectionContract $collection, OutputFactoryContract $outputFactory): OutputContract
    {
        $output = $outputFactory
            ->createOutput();

        $namespace = $route->getArgument('namespace')?->getFirstValue();
        $routes    = $collection->all();
        $colonAt   = false;

        if (is_string($namespace)) {
            $colonAt = strpos($namespace, ':');

            $routes = array_filter($routes, static fn (RouteContract $filterCommand) => str_starts_with($filterCommand->getName(), $namespace));
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
