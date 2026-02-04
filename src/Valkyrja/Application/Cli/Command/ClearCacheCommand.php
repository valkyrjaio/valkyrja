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

namespace Valkyrja\Application\Cli\Command;

use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;

class ClearCacheCommand
{
    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to clear the config cache.');
    }

    #[Route(
        name: 'config:cache:clear',
        description: 'Clear config cache',
        helpText: [self::class, 'help'],
    )]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Banner(new SuccessMessage('Application config cache cleared successfully.')),
                new NewLine(),
            );
    }
}
