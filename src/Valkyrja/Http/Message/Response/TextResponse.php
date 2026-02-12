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
use Override;
use RuntimeException;
use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;

class TextResponse extends Response implements TextResponseContract
{
    /**
     * @param string $text The text
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStreamException
     */
    public function __construct(
        string $text = '',
        StatusCode $statusCode = StatusCode::OK,
        HeaderCollectionContract $headers = new HeaderCollection()
    ) {
        $body = new Stream();

        $body->write($text);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(new Header(HeaderName::CONTENT_TYPE, ContentTypeValue::TEXT_PLAIN_UTF8), $headers, true)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function create(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): static {
        return new static(
            text: $content ?? '',
            statusCode: $statusCode ?? StatusCode::OK,
            headers: $headers ?? new HeaderCollection()
        );
    }
}
