<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Attribute\Route\RequestMethod;

use Attribute;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod as ParentAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends ParentAttribute
{
    public function __construct()
    {
        parent::__construct(
            RequestMethod::PATCH,
        );
    }
}
