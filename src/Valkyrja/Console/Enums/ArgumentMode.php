<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum InputArgument.
 *
 *
 * @author  Melech Mizrachi
 */
final class ArgumentMode extends Enum
{
    public const REQUIRED = 'REQUIRED';
    public const OPTIONAL = 'OPTIONAL';
    public const IS_ARRAY = 'IS_ARRAY';

    protected const VALUES = [
        self::REQUIRED,
        self::OPTIONAL,
        self::IS_ARRAY,
    ];
}
