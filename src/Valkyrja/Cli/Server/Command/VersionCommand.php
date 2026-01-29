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

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;

use const PHP_VERSION;

class VersionCommand
{
    public const string NAME = 'version';

    public function __construct(
        protected OutputFactoryContract $outputFactory
    ) {
    }

    #[Route(
        name: self::NAME,
        description: 'Get the application version',
        helpText: new Message('A command to show the application version and info'),
    )]
    public function run(): OutputContract
    {
        return $this->outputFactory
            ->createOutput()
            ->withMessages(
                new Message(ApplicationContract::ASCII),
                new NewLine(),
                new NewLine(),
                new Message('Valkyrja Framework', new Formatter(new TextColorFormat(TextColor::CYAN))),
                new Message(' version '),
                new Message(ApplicationContract::VERSION, new Formatter(new TextColorFormat(TextColor::MAGENTA))),
                new Message(' (built: '),
                new Message(ApplicationContract::VERSION_BUILD_DATE_TIME, new Formatter(new TextColorFormat(TextColor::MAGENTA))),
                new Message(')'),
                new NewLine(),
                new Message('Copyright (c) Melech Mizrachi'),
                new NewLine(),
                new Message('GitHub https://github.com/valkyrjaio/valkyrja'),
                new NewLine(),
                new Message('Running on PHP ' . PHP_VERSION),
                new NewLine(),
                new NewLine(),
            );
    }
}
