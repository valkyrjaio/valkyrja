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

namespace Valkyrja\Http\Message\Request\Contract;

/**
 * Class JsonRequest.
 *
 * @author Melech Mizrachi
 */
interface JsonServerRequest extends ServerRequest
{
    /**
     * Retrieve any parameters provided in the request body.
     * If the request Content-Type is either application/json
     * and the request method is POST, this method MUST
     * return the decoded contents of the body.
     *
     * @return array<array-key, mixed> The decoded json, if any.
     *                                 These will typically be an array or object.
     */
    public function getParsedJson(): array;

    /**
     * Retrieve only the specified request body params.
     *
     * @param string|int ...$names The param names to retrieve
     *
     * @return array<array-key, mixed>
     */
    public function onlyParsedJson(string|int ...$names): array;

    /**
     * Retrieve all request body params except the ones specified.
     *
     * @param string|int ...$names The param names to not retrieve
     *
     * @return array<array-key, mixed>
     */
    public function exceptParsedJson(string|int ...$names): array;

    /**
     * @param array<array-key, mixed> $data The json params
     *
     * @return static
     */
    public function withParsedJson(array $data): static;

    /**
     * @param string|int $name  The name of the json param
     * @param mixed      $value The value of the json param
     *
     * @return static
     */
    public function withAddedParsedJsonParam(string|int $name, mixed $value): static;

    /**
     * Retrieve a specific json param value.
     * Retrieves a json param value sent by the client to the server.
     *
     * @param string|int $name    The json param name to retrieve
     * @param mixed|null $default [optional] Default value to return if the param does not exist
     *
     * @return mixed
     */
    public function getParsedJsonParam(string|int $name, mixed $default = null): mixed;

    /**
     * Determine if a specific json param exists.
     *
     * @param string|int $name The json param name to check for
     *
     * @return bool
     */
    public function hasParsedJsonParam(string|int $name): bool;
}
