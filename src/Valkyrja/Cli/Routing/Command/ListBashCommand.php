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

namespace Valkyrja\Cli\Routing\Command;

use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;
use Valkyrja\Cli\Routing\Collection\Contract\Collection;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Cli\Routing\Data\OptionParameter;

use function is_string;

/**
 * Class ListBashCommand.
 *
 * @author Melech Mizrachi
 */
class ListBashCommand
{
    public const string NAME = 'list:bash';

    #[CommandAttribute(
        name: self::NAME,
        description: 'List all commands for bash completion',
        helpText: new Message('A command to list all the commands present within the Cli component for bash completion.'),
        parameters: [
            new ArgumentParameter(
                name: 'applicationName',
                description: 'The application name',
            ),
            new OptionParameter(
                name: 'namespace',
                description: 'An optional namespace to filter commands by',
                valueDisplayName: 'namespace',
                shortNames: ['n']
            ),
        ]
    )]
    public function run(Command $command, Collection $collection, OutputFactory $outputFactory): Output
    {
        $output = $outputFactory
            ->createOutput();

        $namespace = $command->getOption('namespace')?->getFirstValue();
        $commands  = $collection->all();
        $colonAt   = false;

        if (is_string($namespace)) {
            $colonAt = strpos($namespace, ':');

            $commands = array_filter($commands, static fn (Command $filterCommand) => str_starts_with($filterCommand->getName(), $namespace));
        }

        $commandsForBash = array_map(
            static fn (Command $command): string => $colonAt !== false ? substr($command->getName(), $colonAt + 1) : $command->getName(),
            $commands
        );

        /** @psalm-suppress ArgumentTypeCoercion */
        return $output
            ->withAddedMessages(
                new Message(implode(' ', $commandsForBash))
            );
    }
}
