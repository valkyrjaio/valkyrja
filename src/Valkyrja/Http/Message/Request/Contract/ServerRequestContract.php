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

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;

interface ServerRequestContract extends RequestContract
{
    /**
     * Get the server params.
     */
    public function getServerParams(): ServerParamCollectionContract;

    /**
     * Create a new instance with the specified server params.
     */
    public function withServerParams(ServerParamCollectionContract $server): static;

    /**
     * Get the cookie params.
     */
    public function getCookieParams(): CookieParamCollectionContract;

    /**
     * Create a new instance with the specified cookie params.
     */
    public function withCookieParams(CookieParamCollectionContract $cookies): static;

    /**
     * Get the query string arguments.
     */
    public function getQueryParams(): QueryParamCollectionContract;

    /**
     * Create a new instance with the specified query string arguments.
     */
    public function withQueryParams(QueryParamCollectionContract $query): static;

    /**
     * Get the uploaded files.
     */
    public function getUploadedFiles(): UploadedFileCollectionContract;

    /**
     * Create a new instance with the specified uploaded files.
     */
    public function withUploadedFiles(UploadedFileCollectionContract $uploadedFiles): static;

    /**
     * Get the parsed body parameters.
     */
    public function getParsedBody(): ParsedBodyParamCollectionContract;

    /**
     * Create a new instance with the specified parsed body parameters.
     */
    public function withParsedBody(ParsedBodyParamCollectionContract $params): static;

    /**
     * Retrieve attributes derived from the request.
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array<array-key, mixed> Attributes derived from the request
     */
    public function getAttributes(): array;

    /**
     * Retrieve only the specified attributes.
     *
     * @param string ...$names The attribute names to retrieve
     *
     * @return array<array-key, mixed>
     */
    public function onlyAttributes(string ...$names): array;

    /**
     * Retrieve all attributes except the ones specified.
     *
     * @param string ...$names The attribute names to not retrieve
     *
     * @return array<array-key, mixed>
     */
    public function exceptAttributes(string ...$names): array;

    /**
     * Retrieve a single derived request attribute.
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @param string     $name    The attribute name
     * @param mixed|null $default Default value to return if the attribute does not exist
     *
     * @see getAttributes()
     */
    public function getAttribute(string $name, mixed $default = null): mixed;

    /**
     * Return an instance with the specified derived request attribute.
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @param string $name  The attribute name
     * @param mixed  $value The value of the attribute
     *
     * @see getAttributes()
     */
    public function withAttribute(string $name, mixed $value): static;

    /**
     * Return an instance that removes the specified derived request attribute.
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @param string $name The attribute name
     *
     * @see getAttributes()
     */
    public function withoutAttribute(string $name): static;

    /**
     * Is this an AJAX request?
     */
    public function isXmlHttpRequest(): bool;
}
