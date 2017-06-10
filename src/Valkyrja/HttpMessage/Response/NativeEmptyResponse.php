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
use Valkyrja\HttpMessage\NativeResponse;

/**
 * Class NativeEmptyResponse.
 *
 * @author Melech Mizrachi
 */
class NativeEmptyResponse extends NativeResponse implements RedirectResponse
{
    /**
     * NativeEmptyResponse constructor.
     *
     * @param array $headers [optional] The headers
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStatusCode
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(array $headers = [])
    {
        parent::__construct(
            null,
            StatusCode::NO_CONTENT,
            $headers
        );
    }
}
