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

use Valkyrja\Application\Contract\Application;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;
use Valkyrja\Cli\Routing\Collection\Contract\Collection;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Cli\Routing\Data\OptionParameter;

use function is_string;

/**
 * Class ListCommand.
 *
 * @author Melech Mizrachi
 */
class ListCommand
{
    public const string NAME = 'list';

    #[CommandAttribute(
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
    public function run(Command $command, Collection $collection, OutputFactory $outputFactory): Output
    {
        $output = $outputFactory
            ->createOutput()
            ->withMessages(
                new NewLine(),
                new Message('Valkyrja version ' . Application::VERSION),
                new NewLine(),
            )
            ->writeMessages();

        $namespace = $command->getOption('namespace')?->getFirstValue();
        $commands  = $collection->all();

        if (is_string($namespace)) {
            $commands = array_filter($commands, static fn (Command $filterCommand) => str_starts_with($filterCommand->getName(), $namespace));
        }

        if ($commands === []) {
            return $output
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage("Namespace `$namespace` was not found."))
                );
        }

        $namespace ??= '';

        return $output
            ->withAddedMessages(
                new Banner(new SuccessMessage("Namespace `$namespace`")),
            );
    }
}
