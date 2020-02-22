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

namespace Valkyrja\Container\Annotation\Enums;

use Valkyrja\Container\Annotation\Models\Service;
use Valkyrja\Container\Annotation\Models\Alias;
use Valkyrja\Container\Annotation\Models\Context;
use Valkyrja\Enum\Enum;

/**
 * Enum AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass extends Enum
{
    public const SERVICE         = Service::class;
    public const SERVICE_ALIAS   = Alias::class;
    public const SERVICE_CONTEXT = Context::class;
}
