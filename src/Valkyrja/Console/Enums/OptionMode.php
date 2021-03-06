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
 * Enum InputOption.
 *
 * @author Melech Mizrachi
 *
 * @method static OptionMode NONE()
 * @method static OptionMode REQUIRED()
 * @method static OptionMode OPTIONAL()
 * @method static OptionMode IS_ARRAY()
 */
final class OptionMode extends Enum
{
    public const NONE     = 'NONE';
    public const REQUIRED = 'REQUIRED';
    public const OPTIONAL = 'OPTIONAL';
    public const IS_ARRAY = 'IS_ARRAY';

    protected static ?array $VALUES = [
        self::NONE     => self::NONE,
        self::REQUIRED => self::REQUIRED,
        self::OPTIONAL => self::OPTIONAL,
        self::IS_ARRAY => self::IS_ARRAY,
    ];
}
