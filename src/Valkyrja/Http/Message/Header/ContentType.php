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
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

class ContentType extends Header
{
    /**
     * @param ValueContract|non-empty-string ...$values The content type values
     */
    public function __construct(ValueContract|string ...$values)
    {
        parent::__construct(HeaderName::CONTENT_TYPE, ...$values);
    }
}
