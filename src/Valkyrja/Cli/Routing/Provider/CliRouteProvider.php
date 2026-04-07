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

namespace Valkyrja\Cli\Routing\Provider;

use Override;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Cli\Server\Command\GenerateDataCommand;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;

class CliRouteProvider implements CliRouteProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getControllerClasses(): array
    {
        return [
            HelpCommand::class,
            ListBashCommand::class,
            ListCommand::class,
            VersionCommand::class,
            GenerateDataCommand::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getRoutes(): array
    {
        return [];
    }
}
