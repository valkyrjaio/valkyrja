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

namespace Valkyrja\Client\Contract;

use Valkyrja\Client\Driver\Contract\Driver;
use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Http\Response\Contract\Response;
use Valkyrja\Manager\Contract\Manager;

/**
 * Interface Client.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory>
 */
interface Client extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(string|null $name = null): Driver;

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
