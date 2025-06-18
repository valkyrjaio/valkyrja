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
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    /** @var array<string, class-string> */
    public const array MAP = [
        AnnotationName::COMMAND         => AnnotationClass::COMMAND,
        AnnotationName::SERVICE         => AnnotationClass::SERVICE,
        AnnotationName::SERVICE_ALIAS   => AnnotationClass::SERVICE_ALIAS,
        AnnotationName::SERVICE_CONTEXT => AnnotationClass::SERVICE_CONTEXT,
    ];
}
