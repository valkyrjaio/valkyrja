<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Response;

use InvalidArgumentException;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Response\Contract\EmptyResponseContract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidStreamException;

class EmptyResponse extends Response implements EmptyResponseContract
{
    /**
     * @throws InvalidArgumentException
     * @throws HttpStreamInvalidStreamException
     */
    public function __construct(
        HeaderCollectionContract $headers = new HeaderCollection()
    ) {
        parent::__construct(
            body: new Stream(mode: Mode::READ),
            statusCode: StatusCode::NO_CONTENT,
            headers: $headers
        );
    }
}
