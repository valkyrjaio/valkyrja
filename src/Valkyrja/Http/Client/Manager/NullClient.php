<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Client\Manager;

use Override;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;

class NullClient implements ClientContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function sendRequest(RequestContract $request): ResponseContract
    {
        return new EmptyResponse();
    }
}
