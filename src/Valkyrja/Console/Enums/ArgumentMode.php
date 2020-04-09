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

namespace Valkyrja\Console\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum InputArgument.
 *
 * @author Melech Mizrachi
 */
final class ArgumentMode extends Enum
{
    public const REQUIRED = 'REQUIRED';
    public const OPTIONAL = 'OPTIONAL';
    public const IS_ARRAY = 'IS_ARRAY';

    protected static ?array $VALUES = [
        self::REQUIRED => self::REQUIRED,
        self::OPTIONAL => self::OPTIONAL,
        self::IS_ARRAY => self::IS_ARRAY,
    ];
}
