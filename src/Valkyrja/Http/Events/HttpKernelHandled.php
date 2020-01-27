<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Events;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Model\Model;

/**
 * Class HttpKernelHandled.
 */
class HttpKernelHandled extends Model
{
    /**
     * The request.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The response.
     *
     * @var Response
     */
    protected Response $response;

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

    /**
     * Get the request.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Set the request.
     *
     * @param Request $request
     *
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Get the response.
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Set the response.
     *
     * @param Response $response
     *
     * @return void
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}
