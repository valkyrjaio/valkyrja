<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Attribute\Route;

use Attribute;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

#[Attribute(Attribute::TARGET_METHOD)]
class ResponseStruct
{
    public function __construct(
        public ResponseStructContract $struct
    ) {
    }
}
