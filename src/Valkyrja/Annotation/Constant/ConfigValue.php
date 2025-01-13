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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ENABLED = false;
    public const MAP     = [
        AnnotationName::COMMAND         => AnnotationClass::COMMAND,
        AnnotationName::LISTENER        => AnnotationClass::LISTENER,
        AnnotationName::SERVICE         => AnnotationClass::SERVICE,
        AnnotationName::SERVICE_ALIAS   => AnnotationClass::SERVICE_ALIAS,
        AnnotationName::SERVICE_CONTEXT => AnnotationClass::SERVICE_CONTEXT,
    ];
    public const ALIASES = [
        Alias::REQUEST_METHOD => AliasClass::REQUEST_METHOD,
        Alias::STATUS_CODE    => AliasClass::STATUS_CODE,
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::ENABLED => self::ENABLED,
        CKP::MAP     => self::MAP,
        CKP::ALIASES => self::ALIASES,
    ];
}
