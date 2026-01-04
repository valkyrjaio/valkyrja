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

namespace Valkyrja\Tests\Classes\Cli\Routing\Command;

use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;

/**
 * Command class to test commands.
 */
class CommandClass
{
    /** @var non-empty-string */
    public const string NAME = 'test';
    /** @var non-empty-string */
    public const string DESCRIPTION = 'A test command';
    /** @var non-empty-string */
    public const string HELP_TEXT = 'A test command';

    #[Route(
        name: self::NAME,
        description: self::DESCRIPTION,
        helpText: new Message(self::HELP_TEXT),
        parameters: [
            new OptionParameter(
                name: 'command',
                description: 'The name of the command to get help for',
                valueDisplayName: 'command',
                mode: OptionMode::REQUIRED
            ),
        ]
    )]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory->createOutput()->withMessages(new Message(self::NAME));
    }
}
