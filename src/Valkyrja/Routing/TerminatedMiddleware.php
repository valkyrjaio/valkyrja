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

namespace Valkyrja\Routing;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Interface TerminatedMiddleware.
 *
 * @author Melech Mizrachi
 */
interface TerminatedMiddleware
{
    /**
     * Middleware handler ran when the application has terminated.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    public static function terminated(Request $request, Response $response): void;
}
