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

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

class ListCommand
{
    public const string NAME = 'http:list';

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to list all the routes present within the Http component.');
    }

    #[Route(
        name: self::NAME,
        description: 'List all routes',
        helpText: [self::class, 'help'],
    )]
    public function run(VersionCommand $version, CollectionContract $collection, OutputFactoryContract $outputFactory): OutputContract
    {
        $output = $outputFactory
            ->createOutput();

        $routes = $collection->allFlattened();

        if ($routes === []) {
            return $output
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage('No routes were found'))
                );
        }

        usort($routes, static fn (RouteContract $a, RouteContract $b): int => $a->getPath() <=> $b->getPath());

        $output = $version
            ->run()
            ->withAddedMessages(
                new NewLine(),
                new Message('Routes:', new HighlightedTextFormatter()),
                new NewLine()
            );

        foreach ($routes as $route) {
            $output = $output->withAddedMessages(
                new Message('  '),
                new Message($route->getPath(), new Formatter(new TextColorFormat(TextColor::MAGENTA))),
                new NewLine(),
                new Message('    - '),
                new Message('Name: '),
                new Message($route->getName(), new HighlightedTextFormatter()),
                new NewLine(),
                new Message('    - '),
                new Message('Dispatch: '),
                new Message($route->getDispatch()->__toString(), new HighlightedTextFormatter()),
                new NewLine(),
            );

            $regex = $route->getRegex();

            if ($regex !== null) {
                $output = $output->withAddedMessages(
                    new Message('    - '),
                    new Message('Regex: '),
                    new Message($regex, new HighlightedTextFormatter()),
                    new NewLine(),
                );
            }
        }

        return $output->withAddedMessages(new NewLine());
    }
}
