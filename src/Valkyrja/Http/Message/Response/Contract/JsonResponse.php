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

namespace Valkyrja\Http\Message\Response\Contract;

/**
 * Interface JsonResponse.
 *
 * @author Melech Mizrachi
 */
interface JsonResponse extends Response
{
    /**
     * Create a JSON response.
     *
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return static
     */
    public static function createFromData(array|null $data = null, int|null $statusCode = null, array|null $headers = null): static;

    /**
     * With callback.
     *
     * @param string $callback The callback
     *
     * @return static
     */
    public function withCallback(string $callback): static;

    /**
     * Without callback.
     *
     * @return static
     */
    public function withoutCallback(): static;
}
