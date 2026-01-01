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

namespace Valkyrja\Application\Provider;

use Override;
use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Provider\Provider as AppComponent;

/**
 * Final Class Component.
 */
class ComponentProvider extends AppComponent
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliControllers(): array
    {
        return [
            CacheCommand::class,
            ClearCacheCommand::class,
        ];
    }
}
