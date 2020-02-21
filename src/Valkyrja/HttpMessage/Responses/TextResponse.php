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

namespace Valkyrja\HttpMessage\Responses;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\HttpMessage\Enums\Header;
use Valkyrja\HttpMessage\Exceptions\InvalidStatusCode;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\Streams\Stream;
use Valkyrja\HttpMessage\TextResponse as TextResponseContract;

/**
 * Class NativeTextResponse.
 *
 * @author Melech Mizrachi
 */
class TextResponse extends Response implements TextResponseContract
{
    /**
     * NativeTextResponse constructor.
     *
     * @param string $text    The text
     * @param int    $status  [optional] The status
     * @param array  $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $text = '', int $status = null, array $headers = [])
    {
        $body = new Stream('php://temp', 'wb+');

        $body->write($text);
        $body->rewind();

        parent::__construct(
            $body,
            $status,
            $this->injectHeader(Header::CONTENT_TYPE, 'text/plain; charset=utf-8', $headers)
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
            TextResponseContract::class,
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
        $app->container()->singleton(TextResponseContract::class, new static());
    }
}
