<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Handler\Contract;

use Throwable;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

interface ExceptionResponseRequestHandlerContract extends RequestHandlerContract
{
    public function createResponseFromException(Throwable $exception): ResponseContract;
}
