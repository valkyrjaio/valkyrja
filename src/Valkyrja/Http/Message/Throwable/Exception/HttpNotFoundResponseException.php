<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;

class HttpNotFoundResponseException extends HttpResponseException
{
    /**
     * @param string|null $message [optional] The Exception message to throw
     */
    public function __construct(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        HeaderCollectionContract|null $headers = null
    ) {
        parent::__construct($statusCode ?? StatusCode::NOT_FOUND, $message, $headers);
    }
}
