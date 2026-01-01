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

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
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

use function is_string;

/**
 * Class ListCommand.
 */
class ListCommand
{
    public const string NAME = 'list';

    #[Route(
        name: self::NAME,
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
    public function run(VersionCommand $version, RouteContract $route, CollectionContract $collection, OutputFactoryContract $outputFactory): OutputContract
    {
        $namespace = $route->getOption('namespace')?->getFirstValue();
        $routes    = $collection->all();

        if (is_string($namespace)) {
            $routes = array_filter($routes, static fn (RouteContract $filterCommand) => str_starts_with($filterCommand->getName(), $namespace));
        }

        if ($routes === []) {
            return $outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage("Namespace `$namespace` was not found."))
                );
        }

        $namespace ??= '';

        usort($routes, static fn (RouteContract $a, RouteContract $b): int => $a->getName() <=> $b->getName());

        $output = $version
            ->run($outputFactory)
            ->withAddedMessages(
                new NewLine(),
                new Message('Commands' . ($namespace !== '' ? " [$namespace]:" : ':'), new HighlightedTextFormatter()),
                new NewLine()
            );

        foreach ($routes as $item) {
            $output = $output->withAddedMessages(
                new Message('  '),
                new Message($item->getName(), new Formatter(textColor: TextColor::MAGENTA)),
                new NewLine(),
                new Message('    - '),
                new Message($item->getDescription(), new HighlightedTextFormatter()),
                new NewLine(),
            );
        }

        return $output->withAddedMessages(new NewLine());
    }
}
