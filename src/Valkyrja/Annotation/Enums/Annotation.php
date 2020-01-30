<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Console\Annotations\Command;
use Valkyrja\Container\Annotations\Service;
use Valkyrja\Container\Annotations\ServiceAlias;
use Valkyrja\Container\Annotations\ServiceContext;
use Valkyrja\Enum\Enum;
use Valkyrja\Event\Annotations\Listener;
use Valkyrja\Routing\Annotations\Route;

/**
 * Enum Annotation.
 *
 * @author Melech Mizrachi
 */
final class Annotation extends Enum
{
    public const COMMAND         = Command::class;
    public const LISTENER        = Listener::class;
    public const ROUTE           = Route::class;
    public const SERVICE         = Service::class;
    public const SERVICE_ALIAS   = ServiceAlias::class;
    public const SERVICE_CONTEXT = ServiceContext::class;
}
