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

namespace Valkyrja\Cli\Provider;

use Override;
use Valkyrja\Application\Provider\Provider as AppComponent;
use Valkyrja\Cli\Command\HelpCommand;
use Valkyrja\Cli\Command\ListBashCommand;
use Valkyrja\Cli\Command\ListCommand;
use Valkyrja\Cli\Command\VersionCommand;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider as CliServiceProvider;
use Valkyrja\Cli\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Cli\Routing\Provider\ServiceProvider as RoutingServiceProvider;
use Valkyrja\Cli\Server\Provider\ServiceProvider as ServerServiceProvider;

/**
 * Final Class Component.
 *
 * @author Melech Mizrachi
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
            CliServiceProvider::class,
            MiddlewareServiceProvider::class,
            RoutingServiceProvider::class,
            ServerServiceProvider::class,
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
