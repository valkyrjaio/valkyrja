<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\ContentType;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\Stream as StreamEnum;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\HtmlResponse as HtmlResponseContract;
use Valkyrja\Http\Streams\Stream;

/**
 * Class HtmlResponse.
 *
 * @author Melech Mizrachi
 */
class HtmlResponse extends Response implements HtmlResponseContract
{
    /**
     * NativeHtmlResponse constructor.
     *
     * @param string     $html    The html
     * @param int|null   $status  [optional] The status
     * @param array|null $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $html = '', int $status = null, array $headers = [])
    {
        $body = new Stream(StreamEnum::TEMP, 'wb+');

        $body->write($html);
        $body->rewind();

        parent::__construct(
            $body,
            $status,
            $this->injectHeader(Header::CONTENT_TYPE, ContentType::TEXT_HTML_UTF8, $headers)
        );
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            HtmlResponseContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(HtmlResponseContract::class, new static());
    }
}
