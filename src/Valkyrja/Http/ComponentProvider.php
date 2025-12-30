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

namespace Valkyrja\Http;

use Override;
use Valkyrja\Application\Provider\Provider as AppComponent;
use Valkyrja\Http\Client\Provider\ServiceProvider as ClientServiceProvider;
use Valkyrja\Http\Message\Provider\ServiceProvider as MessageServiceProvider;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Provider\ServiceProvider as RoutingServiceProvider;
use Valkyrja\Http\Server\Provider\ServiceProvider as ServerServiceProvider;

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
            ClientServiceProvider::class,
            MessageServiceProvider::class,
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
            ListCommand::class,
        ];
    }
}
