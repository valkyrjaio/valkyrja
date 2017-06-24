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
 * Class NativeJsonResponse.
 *
 * @author Melech Mizrachi
 */
class NativeJsonResponse extends NativeResponse implements JsonResponse
{
    /**
     * The default encoding options to use for json_encode().
     *
     * @constant int
     */
    protected const DEFAULT_ENCODING_OPTIONS = 79;

    /**
     * NativeJsonResponse constructor.
     *
     * @param array $data            The data
     * @param int   $status          [optional] The status
     * @param array $headers         [optional] The headers
     * @param int   $encodingOptions [optional] The encoding options
     *
     * @throws \RuntimeException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStatusCode
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(
        array $data,
        int $status = null,
        array $headers = null,
        int $encodingOptions = null
    ) {
        $body = new NativeStream('php://temp', 'wb+');

        $body->write(
            json_encode(
                $data,
                $encodingOptions ?? static::DEFAULT_ENCODING_OPTIONS
            )
        );
        $body->rewind();

        parent::__construct(
            $body,
            $status,
            $this->injectHeader(
                Header::CONTENT_TYPE,
                'application/json',
                $headers
            )
        );
    }
}
