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

namespace Valkyrja\Http\Routing\Provider;

use Override;
use Valkyrja\Application\Provider\Provider as AppComponent;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;

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
            ListCommand::class,
        ];
    }
}
