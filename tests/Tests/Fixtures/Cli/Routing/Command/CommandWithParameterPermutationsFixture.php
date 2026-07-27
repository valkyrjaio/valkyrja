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
 * Command fixture exercising the argument/option mode and value-mode permutations not
 * covered by CommandWithAllAttributesFixture (which only covers REQUIRED + ARRAY): an
 * optional single-value argument, an optional single-value long-only option, and a
 * valueless (NONE) flag option.
 */
final class CommandWithParameterPermutationsFixture
{
    /** @var non-empty-string */
    public const string NAME = 'permutations';
    /** @var non-empty-string */
    public const string DESCRIPTION = 'A permutations command';
    /** @var non-empty-string */
    public const string HELP_TEXT = 'A permutations command';

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
        name: 'optionalArgument',
        description: 'An optional single-value argument',
        mode: ArgumentMode::OPTIONAL,
        valueMode: ArgumentValueMode::DEFAULT,
    )]
    #[OptionParameter(
        name: 'optionalOption',
        description: 'An optional single-value long-only option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::DEFAULT,
    )]
    #[OptionParameter(
        name: 'flag',
        description: 'A valueless flag option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::NONE,
    )]
    public function run(OutputFactoryContract $outputFactory): OutputContract
    {
        return $outputFactory->createOutput()->withMessages(new Message(self::NAME));
    }
}
