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

namespace Valkyrja\Http\Routing\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Interface Router.
 *
 * @author Melech Mizrachi
 */
interface Router
{
    /**
     * Dispatch a server request.
     */
    public function dispatch(ServerRequest $request): Response;

    /**
     * Dispatch a server request for a specific route.
     */
    public function dispatchRoute(ServerRequest $request, Route $route): Response;
}
