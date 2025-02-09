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

namespace Valkyrja\View\Factory\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface ResponseFactory.
 *
 * @author Melech Mizrachi
 */
interface ResponseFactory
{
    /**
     * View response builder.
     *
     * @param string                       $template   The view template to use
     * @param array<string, mixed>|null    $data       [optional] The view data
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function createResponseFromView(
        string $template,
        ?array $data = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): Response;
}
