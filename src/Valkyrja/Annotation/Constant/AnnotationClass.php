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

namespace Valkyrja\Annotation\Constant;

use Valkyrja\Console\Annotation\Command;
use Valkyrja\Container\Annotation\Service;
use Valkyrja\Container\Annotation\Service\Alias;
use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Event\Annotation\Listener;

/**
 * Constant AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass
{
    public const COMMAND         = Command::class;
    public const LISTENER        = Listener::class;
    public const SERVICE         = Service::class;
    public const SERVICE_ALIAS   = Alias::class;
    public const SERVICE_CONTEXT = Context::class;
}
