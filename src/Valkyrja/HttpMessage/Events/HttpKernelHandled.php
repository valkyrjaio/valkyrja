<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Events;

use Valkyrja\HttpMessage\Request;
use Valkyrja\HttpMessage\Response;

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
     * @var Request
     */
    public Request $request;

    /**
     * The response.
     *
     * @var Response
     */
    public Response $response;

    /**
     * HttpKernelHandled constructor.
     *
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }
}
