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
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;

use const PHP_VERSION;

/**
 * Class VersionCommand.
 *
 * @author Melech Mizrachi
 */
class VersionCommand
{
    public const string NAME = 'version';

    #[CommandAttribute(
        name: self::NAME,
        description: 'Get the application version',
        helpText: new Message('A command to show the application version and info'),
    )]
    public function run(OutputFactory $outputFactory): Output
    {
        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Message(Application::ASCII),
                new NewLine(),
                new NewLine(),
                new Message('Valkyrja Framework', new Formatter(textColor: TextColor::CYAN)),
                new Message(' version '),
                new Message(Application::VERSION, new Formatter(textColor: TextColor::MAGENTA)),
                new NewLine(),
                new Message('Copyright (c) Melech Mizrachi'),
                new NewLine(),
                new Message('Github https://github.com/valkyrjaio/valkyrja'),
                new NewLine(),
                new Message('Running on PHP ' . PHP_VERSION),
                new NewLine(),
                new NewLine(),
            )
            ->writeMessages();
    }
}
