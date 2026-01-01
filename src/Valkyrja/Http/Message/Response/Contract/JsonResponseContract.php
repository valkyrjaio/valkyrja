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

use Valkyrja\Http\Message\Enum\StatusCode;

/**
 * Interface JsonResponseContract.
 */
interface JsonResponseContract extends ResponseContract
{
    /**
     * Create a JSON response.
     *
     * @param array<array-key, mixed>|null $data       [optional] The data to set
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return static
     */
    public static function createFromData(
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static;

    /**
     * Get the body as a json array.
     *
     * @return array<array-key, mixed>
     */
    public function getBodyAsJson(): array;

    /**
     * Create a new JsonResponse with the given data.
     *
     * @param array<array-key, mixed> $data the data
     *
     * @return static
     */
    public function withJsonAsBody(array $data): static;

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
