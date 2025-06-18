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
use Valkyrja\Http\Message\Response\Contract\TextResponse as Contract;
use Valkyrja\Http\Message\Stream\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Stream;

/**
 * Class TextResponse.
 *
 * @author Melech Mizrachi
 */
class TextResponse extends Response implements Contract
{
    /**
     * NativeTextResponse constructor.
     *
     * @param string                  $text       The text
     * @param StatusCode              $statusCode [optional] The status
     * @param array<string, string[]> $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStreamException
     */
    public function __construct(
        string $text = self::DEFAULT_CONTENT,
        StatusCode $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        $body = new Stream();

        $body->write($text);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(HeaderName::CONTENT_TYPE, ContentType::TEXT_PLAIN_UTF8, $headers, true)
        );
    }

    /**
     * @inheritDoc
     */
    public static function create(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static {
        return new static(
            text: $content,
            statusCode: $statusCode ?? static::DEFAULT_STATUS_CODE,
            headers: $headers ?? static::DEFAULT_HEADERS
        );
    }
}
