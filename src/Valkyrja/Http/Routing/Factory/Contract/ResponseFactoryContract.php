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
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;

interface ResponseFactoryContract
{
    /**
     * Redirect to a named route response builder.
     *
     * @param non-empty-string               $name The name of the route
     * @param array<string, string|int>|null $data [optional] The data for dynamic routes
     */
    public function createRouteRedirectResponse(
        string $name,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): RedirectResponseContract;
}
