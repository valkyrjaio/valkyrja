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

namespace Valkyrja\Tests\Fixtures\Cli\Routing\Command;

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter;
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * Command fixture exercising a full matrix of argument and option modes/value-modes so the
 * attribute construction path can be asserted to convert every permutation into the
 * expected data-class parameters. Mirrors the Java CliRoutingCombinationsController.
 */
final class CommandWithParameterCombinationsFixture
{
    /** @var non-empty-string */
    public const string NAME = 'combinations';
    /** @var non-empty-string */
    public const string DESCRIPTION = 'A combinations command';
    /** @var non-empty-string */
    public const string HELP_TEXT = 'A combinations command';

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message(self::HELP_TEXT);
    }

    /**
     * Handler for the command.
     */
    public static function handler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return new self()->run(
            $container->getSingleton(OutputFactoryContract::class)
        );
    }

    #[Route(
        name: self::NAME,
        description: self::DESCRIPTION,
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([self::class, 'handler'])]
    #[ArgumentParameter(
        name: 'required',
        description: 'A required single-value argument',
        mode: ArgumentMode::REQUIRED,
        valueMode: ArgumentValueMode::DEFAULT,
    )]
    #[ArgumentParameter(
        name: 'rest',
        description: 'An optional array argument',
        mode: ArgumentMode::OPTIONAL,
        valueMode: ArgumentValueMode::ARRAY,
    )]
    #[OptionParameter(
        name: 'format',
        description: 'A required single-value option',
        valueDisplayName: 'fmt',
        defaultValue: 'json',
        shortNames: ['f'],
        validValues: ['json', 'xml'],
        mode: OptionMode::REQUIRED,
        valueMode: OptionValueMode::DEFAULT,
    )]
    #[OptionParameter(
        name: 'flag',
        description: 'A valueless flag option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::NONE,
    )]
    #[OptionParameter(
        name: 'tags',
        description: 'A repeatable array option',
        valueMode: OptionValueMode::ARRAY,
    )]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory->createOutput()->withMessages(new Message(self::NAME));
    }
}
