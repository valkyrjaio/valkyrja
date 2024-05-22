<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Response;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Http\Constant\ContentType;
use Valkyrja\Http\Constant\Header;
use Valkyrja\Http\Constant\StreamType;
use Valkyrja\Http\Exception\InvalidStatusCode;
use Valkyrja\Http\Exception\InvalidStream;
use Valkyrja\Http\Response\Contract\HtmlResponse as Contract;
use Valkyrja\Http\Stream\Stream;

/**
 * Class HtmlResponse.
 *
 * @author Melech Mizrachi
 */
class HtmlResponse extends Response implements Contract
{
    /**
     * NativeHtmlResponse constructor.
     *
     * @param string $html       The html
     * @param int    $statusCode [optional] The status
     * @param array  $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(
        string $html = self::DEFAULT_CONTENT,
        int $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        $body = new Stream(StreamType::TEMP, 'wb+');

        $body->write($html);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(Header::CONTENT_TYPE, ContentType::TEXT_HTML_UTF8, $headers)
        );
    }
}
