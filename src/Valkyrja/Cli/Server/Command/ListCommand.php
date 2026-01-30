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

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Server\Constant\CommandName;

use function is_string;

class ListCommand
{
    public function __construct(
        protected VersionCommand $version,
        protected RouteContract $route,
        protected CollectionContract $collection,
        protected OutputFactoryContract $outputFactory
    ) {
    }

    #[Route(
        name: CommandName::LIST,
        description: 'List all commands',
        helpText: new Message('A command to list all the commands present within the Cli component.'),
        parameters: [
            new OptionParameter(
                name: 'namespace',
                description: 'An optional namespace to filter commands by',
                valueDisplayName: 'namespace',
                shortNames: ['n']
            ),
        ]
    )]
    public function run(): OutputContract
    {
        $namespace = $this->route->getOption('namespace')?->getFirstValue();
        $routes    = $this->collection->all();

        if (is_string($namespace)) {
            $routes = array_filter($routes, static fn (RouteContract $route) => str_starts_with($route->getName(), $namespace));

            if ($routes === []) {
                return $this->outputFactory
                    ->createOutput()
                    ->withExitCode(ExitCode::ERROR)
                    ->withAddedMessages(
                        new Banner(new ErrorMessage("Namespace `$namespace` was not found."))
                    );
            }
        }

        if ($routes === []) {
            return $this->outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage('No routes found.'))
                );
        }

        $namespace ??= '';

        usort($routes, static fn (RouteContract $a, RouteContract $b): int => $a->getName() <=> $b->getName());

        $output = $this->version
            ->run()
            ->withAddedMessages(
                new NewLine(),
                new Message('Commands' . ($namespace !== '' ? " [$namespace]:" : ':'), new HighlightedTextFormatter()),
                new NewLine()
            );

        foreach ($routes as $route) {
            $output = $output->withAddedMessages(
                new Message('  '),
                new Message($route->getName(), new Formatter(new TextColorFormat(TextColor::MAGENTA))),
                new NewLine(),
                new Message('    - '),
                new Message($route->getDescription(), new HighlightedTextFormatter()),
                new NewLine(),
            );
        }

        return $output->withAddedMessages(new NewLine());
    }
}
