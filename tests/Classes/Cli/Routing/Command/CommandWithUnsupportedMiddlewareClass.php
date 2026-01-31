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

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\Middleware;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\RouteNotMatchedHandlerClass;

class CommandWithUnsupportedMiddlewareClass
{
    /** @var non-empty-string */
    public const string NAME = 'test2';
    /** @var non-empty-string */
    public const string DESCRIPTION = 'A test2 command';
    /** @var non-empty-string */
    public const string HELP_TEXT = 'A test2 command';

    #[Route(
        name: self::NAME,
        description: self::DESCRIPTION,
        helpText: new Message(self::HELP_TEXT),
    )]
    #[Middleware(RouteNotMatchedHandlerClass::class)]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory->createOutput()->withMessages(new Message(self::NAME));
    }
}
