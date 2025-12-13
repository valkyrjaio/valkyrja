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

namespace Valkyrja\Cli;

use Override;
use Valkyrja\Application\Support\Component as AppComponent;

/**
 * Final Class Component.
 *
 * @author Melech Mizrachi
 */
class Component extends AppComponent
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(): array
    {
        return [
            Interaction\Provider\ServiceProvider::class,
            Middleware\Provider\ServiceProvider::class,
            Routing\Provider\ServiceProvider::class,
            Server\Provider\ServiceProvider::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliControllers(): array
    {
        return [
            Command\HelpCommand::class,
            Command\ListBashCommand::class,
            Command\ListCommand::class,
            Command\VersionCommand::class,
        ];
    }
}
