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

use Valkyrja\Http\StatusCode;
use Valkyrja\HttpMessage\Header;
use Valkyrja\HttpMessage\NativeResponse;

/**
 * Class NativeRedirectResponse.
 *
 * @author Melech Mizrachi
 */
class NativeRedirectResponse extends NativeResponse implements RedirectResponse
{
    /**
     * NativeRedirectResponse constructor.
     *
     * @param string $uri     The uri
     * @param int    $status  [optional] The status
     * @param array  $headers [optional] The headers
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStatusCode
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(string $uri, int $status = null, array $headers = [])
    {
        parent::__construct(
            null,
            $status ?? StatusCode::FOUND,
            $this->injectHeader(Header::LOCATION, $uri, $headers, true)
        );
    }
}
