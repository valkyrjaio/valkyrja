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

use Valkyrja\Console\Enum\AnnotationName as ConsoleAnnotation;
use Valkyrja\Container\Enum\AnnotationName as ContainerAnnotation;
use Valkyrja\Event\Enum\AnnotationName as EventAnnotation;

/**
 * Constant AnnotationName.
 *
 * @author Melech Mizrachi
 */
final class AnnotationName
{
    public const COMMAND         = ConsoleAnnotation::COMMAND;
    public const LISTENER        = EventAnnotation::LISTENER;
    public const SERVICE         = ContainerAnnotation::SERVICE;
    public const SERVICE_ALIAS   = ContainerAnnotation::SERVICE_ALIAS;
    public const SERVICE_CONTEXT = ContainerAnnotation::SERVICE_CONTEXT;
}
