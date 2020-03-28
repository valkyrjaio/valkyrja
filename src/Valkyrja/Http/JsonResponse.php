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

namespace Valkyrja\Http;

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
     * @param array|null $data            [optional] The data
     * @param int|null   $status          [optional] The status
     * @param array|null $headers         [optional] The headers
     * @param int|null   $encodingOptions [optional] The encoding options
     *
     * @return static
     */
    public static function createJsonResponse(
        array $data = null,
        int $status = null,
        array $headers = null,
        int $encodingOptions = null
    ): self;

    /**
     * With callback.
     *
     * @param string $callback The callback
     *
     * @return static
     */
    public function withCallback(string $callback): self;

    /**
     * Without callback.
     *
     * @return static
     */
    public function withoutCallback(): self;
}
