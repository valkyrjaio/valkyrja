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
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter;
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\Middleware;
use Valkyrja\Cli\Routing\Attribute\Route\Name;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareClass;

/**
 * Command class to test commands.
 */
#[Name('className')]
class CommandWithAllAttributesClass
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
    #[Name('actionName')]
    #[OptionParameter(
        name: 'optionName',
        description: 'The option for the command',
        valueDisplayName: 'name',
        defaultValue: 'foo',
        shortNames: ['o'],
        validValues: ['foo', 'bar'],
        mode: OptionMode::REQUIRED,
        valueMode: OptionValueMode::ARRAY,
    )]
    #[ArgumentParameter(
        name: 'argumentName',
        description: 'The argument for the command',
        mode: ArgumentMode::REQUIRED,
        valueMode: ArgumentValueMode::ARRAY,
    )]
    #[Middleware(RouteDispatchedMiddlewareClass::class)]
    #[Middleware(RouteMatchedMiddlewareClass::class)]
    #[Middleware(ThrowableCaughtMiddlewareClass::class)]
    #[Middleware(ExitedMiddlewareClass::class)]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory->createOutput()->withMessages(new Message(self::NAME));
    }
}
