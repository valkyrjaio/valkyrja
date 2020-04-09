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

namespace Valkyrja\Filesystem\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum Visibility.
 *
 * @author Melech Mizrachi
 */
final class Visibility extends Enum
{
    public const PUBLIC  = 'public';
    public const PRIVATE = 'private';

    protected static ?array $VALUES = [
        self::PUBLIC  => self::PUBLIC,
        self::PRIVATE => self::PRIVATE,
    ];
}
