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

namespace Valkyrja\Client;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Interface Client.
 *
 * @author Melech Mizrachi
 */
interface Client
{
    /**
     * Use a client by name.
     *
     * @param string|null $name    [optional] The connection name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    public function useClient(string $name = null, string $adapter = null): Driver;

    /**
     * Make a request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function request(Request $request): Response;

    /**
     * Make a get request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function get(Request $request): Response;

    /**
     * Make a post request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function post(Request $request): Response;

    /**
     * Make a head request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function head(Request $request): Response;

    /**
     * Make a put request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function put(Request $request): Response;

    /**
     * Make a patch request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function patch(Request $request): Response;

    /**
     * Make a delete request.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function delete(Request $request): Response;
}
