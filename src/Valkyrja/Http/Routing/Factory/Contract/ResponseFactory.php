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

namespace Valkyrja\Http\Routing\Factory\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;

/**
 * Interface ResponseFactory.
 *
 * @author Melech Mizrachi
 */
interface ResponseFactory
{
    /**
     * Redirect to a named route response builder.
     *
     * @param string          $name       The name of the route
     * @param array|null      $data       [optional] The data for dynamic routes
     * @param StatusCode|null $statusCode [optional] The response status code
     * @param array|null      $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function createRouteRedirectResponse(
        string $name,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): RedirectResponse;
}
