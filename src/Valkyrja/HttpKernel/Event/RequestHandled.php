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

namespace Valkyrja\HttpKernel\Event;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Class RequestHandled.
 *
 * @author Melech Mizrachi
 */
class RequestHandled
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
