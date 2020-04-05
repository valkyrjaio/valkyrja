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

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\Http\Enums\ContentType;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\Stream as StreamEnum;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Streams\Stream;
use Valkyrja\Http\TextResponse as Contract;

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
     * @param string     $text       The text
     * @param int|null   $statusCode [optional] The status
     * @param array|null $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $text = '', int $statusCode = null, array $headers = [])
    {
        $body = new Stream(StreamEnum::TEMP, 'wb+');

        $body->write($text);
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(Header::CONTENT_TYPE, ContentType::TEXT_PLAIN_UTF8, $headers)
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
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $container->setSingleton(
            Contract::class,
            new static()
        );
    }
}
