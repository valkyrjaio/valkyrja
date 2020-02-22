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

use Valkyrja\Enum\Enum;

/**
 * Enum Annotation.
 *
 * @author Melech Mizrachi
 */
final class Annotation extends Enum
{
    public const SERVICE         = 'Service';
    public const SERVICE_ALIAS   = 'Service\\Alias';
    public const SERVICE_CONTEXT = 'Service\\Context';
}