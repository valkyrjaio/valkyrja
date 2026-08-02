<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Header;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Value\Contract\CookieContract;

class SetCookie extends Header
{
    /**
     * @param CookieContract ...$values The cookie values
     */
    public function __construct(CookieContract ...$values)
    {
        parent::__construct(HeaderName::SET_COOKIE, ...$values);
    }
}
