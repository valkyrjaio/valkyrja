<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Response\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;

interface JsonResponseContract extends ResponseContract
{
    /**
     * Create a JSON response.
     *
     * @param array<array-key, mixed>|null $data [optional] The data to set
     */
    public static function createFromData(
        array|null $data = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
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
     */
    public function withJsonAsBody(array $data): static;

    /**
     * With callback.
     *
     * @param string $callback The callback
     */
    public function withCallback(string $callback): static;

    /**
     * Without callback.
     */
    public function withoutCallback(): static;
}
