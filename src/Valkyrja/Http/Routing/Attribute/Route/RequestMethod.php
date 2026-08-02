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
use Valkyrja\Http\Message\Enum\RequestMethod as RequestMethodEnum;

#[Attribute(Attribute::TARGET_METHOD)]
class RequestMethod
{
    /** @var RequestMethodEnum[] */
    public array $requestMethods = [];

    public function __construct(RequestMethodEnum ...$requestMethods)
    {
        $this->requestMethods = $requestMethods;
    }
}
