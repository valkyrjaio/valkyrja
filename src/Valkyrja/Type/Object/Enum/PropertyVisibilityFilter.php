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

namespace Valkyrja\Type\Object\Enum;

enum PropertyVisibilityFilter
{
    case ALL;
    case PUBLIC;
    case PROTECTED;
    case PRIVATE;
    case PUBLIC_PROTECTED;
    case PUBLIC_PRIVATE;
    case PRIVATE_PROTECTED;

    public function shouldIncludePublic(): bool
    {
        return $this === self::ALL
            || $this === self::PUBLIC
            || $this === self::PUBLIC_PROTECTED
            || $this === self::PUBLIC_PRIVATE;
    }

    public function shouldIncludeProtected(): bool
    {
        return $this === self::ALL
            || $this === self::PROTECTED
            || $this === self::PUBLIC_PROTECTED
            || $this === self::PRIVATE_PROTECTED;
    }

    public function shouldIncludePrivate(): bool
    {
        return $this === self::ALL
            || $this === self::PRIVATE
            || $this === self::PUBLIC_PRIVATE
            || $this === self::PRIVATE_PROTECTED;
    }
}
