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

namespace Valkyrja\HttpMessage\Events;

use Valkyrja\HttpMessage\Response;
use Valkyrja\HttpMessage\SimpleRequest;

/**
 * Class HttpKernelHandled.
 *
 * @author Melech Mizrachi
 */
class HttpKernelHandled
{
    /**
     * The request.
     *
     * @var SimpleRequest
     */
    public SimpleRequest $request;

    /**
     * The response.
     *
     * @var Response
     */
    public Response $response;

    /**
     * HttpKernelHandled constructor.
     *
     * @param SimpleRequest $request
     * @param Response      $response
     */
    public function __construct(SimpleRequest $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }
}
