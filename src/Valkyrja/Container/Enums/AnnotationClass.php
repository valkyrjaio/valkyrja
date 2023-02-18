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

namespace Valkyrja\Container\Enums;

use Valkyrja\Container\Annotations\Service;
use Valkyrja\Container\Annotations\Service\Alias;
use Valkyrja\Container\Annotations\Service\Context;
use Valkyrja\Type\Types\Enum;

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
