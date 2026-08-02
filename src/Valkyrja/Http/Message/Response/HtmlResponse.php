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
use RuntimeException;
use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Contract\HtmlResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidStreamException;

class HtmlResponse extends Response implements HtmlResponseContract
{
    /**
     * @param string $html The html
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws HttpStreamInvalidStreamException
     */
    public function __construct(
        string $html = '',
        StatusCode $statusCode = StatusCode::OK,
        HeaderCollectionContract $headers = new HeaderCollection()
    ) {
        $body = new Stream();

        $body->write($html);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(new Header(HeaderName::CONTENT_TYPE, ContentTypeValue::TEXT_HTML_UTF8), $headers, true)
        );
    }
}
