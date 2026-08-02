<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Client\Manager\Contract;

use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

interface ClientContract
{
    /**
     * Send a request and recieve a response.
     */
    public function sendRequest(RequestContract $request): ResponseContract;
}
