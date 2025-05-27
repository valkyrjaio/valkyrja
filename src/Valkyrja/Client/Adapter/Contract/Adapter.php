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

namespace Valkyrja\Client\Adapter\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Make a request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function request(ServerRequest $request): Response;

    /**
     * Make a get request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function get(ServerRequest $request): Response;

    /**
     * Make a post request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function post(ServerRequest $request): Response;

    /**
     * Make a head request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function head(ServerRequest $request): Response;

    /**
     * Make a put request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function put(ServerRequest $request): Response;

    /**
     * Make a patch request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function patch(ServerRequest $request): Response;

    /**
     * Make a delete request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function delete(ServerRequest $request): Response;
}
