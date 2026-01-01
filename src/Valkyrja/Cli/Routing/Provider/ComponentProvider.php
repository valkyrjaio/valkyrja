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
use Valkyrja\Application\Provider\Provider as AppComponent;
use Valkyrja\Cli\Command\HelpCommand;
use Valkyrja\Cli\Command\ListBashCommand;
use Valkyrja\Cli\Command\ListCommand;
use Valkyrja\Cli\Command\VersionCommand;

/**
 * Final Class Component.
 */
class ComponentProvider extends AppComponent
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliControllers(): array
    {
        return [
            HelpCommand::class,
            ListBashCommand::class,
            ListCommand::class,
            VersionCommand::class,
        ];
    }
}
