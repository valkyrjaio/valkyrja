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

namespace Valkyrja\Application\Constant;

use Valkyrja\Application\Data\Data;
use Valkyrja\Cli\Routing\Data\Data as CliData;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Http\Routing\Data\Data as HttpData;

final class AllowedClasses
{
    public const array ENTRY_APP = [
        Data::class,
        CliData::class,
        ContainerData::class,
        EventData::class,
        HttpData::class,
    ];
}
