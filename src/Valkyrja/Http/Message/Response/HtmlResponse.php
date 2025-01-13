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

namespace Valkyrja\Http\Message\Response;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\HtmlResponse as Contract;
use Valkyrja\Http\Message\Stream\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Stream;

/**
 * Class HtmlResponse.
 *
 * @author Melech Mizrachi
 */
class HtmlResponse extends Response implements Contract
{
    /**
     * HtmlResponse constructor.
     *
     * @param string                  $xml        The html
     * @param StatusCode              $statusCode [optional] The status
     * @param array<string, string[]> $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStreamException
     */
    public function __construct(
        string $xml = self::DEFAULT_CONTENT,
        StatusCode $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        $body = new Stream();

        $body->write($xml);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(HeaderName::CONTENT_TYPE, ContentType::TEXT_HTML_UTF8, $headers, true)
        );
    }
}
