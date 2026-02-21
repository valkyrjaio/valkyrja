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
use Valkyrja\Cli\Routing\Provider\Provider;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;

class CliRouteProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getControllerClasses(): array
    {
        return [
            ListCommand::class,
        ];
    }
}
