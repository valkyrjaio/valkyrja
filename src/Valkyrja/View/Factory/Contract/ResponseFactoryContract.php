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
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

/**
 * Interface ResponseFactoryContract.
 *
 * @author Melech Mizrachi
 */
interface ResponseFactoryContract
{
    /**
     * View response builder.
     *
     * @param non-empty-string             $template   The view template to use
     * @param array<string, mixed>|null    $data       [optional] The view data
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return ResponseContract
     */
    public function createResponseFromView(
        string $template,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): ResponseContract;
}
