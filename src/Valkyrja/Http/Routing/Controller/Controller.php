<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Controller;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

abstract class Controller
{
    public function __construct(
        protected ServerRequestContract $request,
        protected ResponseFactoryContract $responseFactory,
    ) {
    }
}
