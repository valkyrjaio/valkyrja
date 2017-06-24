<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Response;

use Valkyrja\HttpMessage\Header;
use Valkyrja\HttpMessage\NativeResponse;
use Valkyrja\HttpMessage\NativeStream;

/**
 * Class NativeTextResponse.
 *
 * @author Melech Mizrachi
 */
class NativeTextResponse extends NativeResponse implements TextResponse
{
    /**
     * NativeTextResponse constructor.
     *
     * @param string $text    The text
     * @param int    $status  [optional] The status
     * @param array  $headers [optional] The headers
     *
     * @throws \RuntimeException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStatusCode
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(
        string $text,
        int $status = null,
        array $headers = []
    ) {
        $body = new NativeStream('php://temp', 'wb+');

        $body->write($text);
        $body->rewind();

        parent::__construct(
            $body,
            $status,
            $this->injectHeader(
                Header::CONTENT_TYPE,
                'text/plain; charset=utf-8',
                $headers
            )
        );
    }
}
