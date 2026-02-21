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
use Valkyrja\Cli\Routing\Provider\Provider;

class CliRouteProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getControllerClasses(): array
    {
        return [
            CacheCommand::class,
            ClearCacheCommand::class,
        ];
    }
}
