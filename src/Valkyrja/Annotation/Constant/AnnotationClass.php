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

use Valkyrja\Console\Enum\AnnotationClass as ConsoleAnnotationClass;
use Valkyrja\Container\Enum\AnnotationClass as ContainerAnnotationClass;
use Valkyrja\Event\Enum\AnnotationClass as EventAnnotationClass;

/**
 * Constant AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass
{
    public const COMMAND         = ConsoleAnnotationClass::COMMAND;
    public const LISTENER        = EventAnnotationClass::LISTENER;
    public const SERVICE         = ContainerAnnotationClass::SERVICE;
    public const SERVICE_ALIAS   = ContainerAnnotationClass::SERVICE_ALIAS;
    public const SERVICE_CONTEXT = ContainerAnnotationClass::SERVICE_CONTEXT;
}
