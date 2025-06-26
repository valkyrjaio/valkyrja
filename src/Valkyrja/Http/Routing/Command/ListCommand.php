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

namespace Valkyrja\Http\Routing\Command;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Http\Routing\Collection\Contract\Collection;

/**
 * Class ListCommand.
 *
 * @author Melech Mizrachi
 */
class ListCommand
{
    public const string NAME = 'http:list';

    #[CommandAttribute(
        name: self::NAME,
        description: 'List all routes',
        helpText: new Message('A command to list all the routes present within the Http component.'),
    )]
    public function run(Command $command, Collection $collection, OutputFactory $outputFactory): Output
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

        return $output
            ->withAddedMessages(
                new Banner(new SuccessMessage('Routes list!')),
            );
    }
}
