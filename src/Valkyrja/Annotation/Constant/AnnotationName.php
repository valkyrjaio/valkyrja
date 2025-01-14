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

/**
 * Constant AnnotationName.
 *
 * @author Melech Mizrachi
 */
final class AnnotationName
{
    public const COMMAND         = 'Command';
    public const LISTENER        = 'listener';
    public const SERVICE         = 'Service';
    public const SERVICE_ALIAS   = 'Service\\Alias';
    public const SERVICE_CONTEXT = 'Service\\Context';
}
