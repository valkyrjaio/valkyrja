<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
