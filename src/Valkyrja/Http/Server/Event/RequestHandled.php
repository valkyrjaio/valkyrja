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

namespace Valkyrja\Http\Server\Event;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

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
     * @var ServerRequest
     */
    public ServerRequest $request;

    /**
     * The response.
     *
     * @var Response
     */
    public Response $response;

    /**
     * HttpKernelHandled constructor.
     *
     * @param ServerRequest $request
     * @param Response      $response
     */
    public function __construct(ServerRequest $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }
}
